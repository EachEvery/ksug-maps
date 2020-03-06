<?php

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Audio\Mp3;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use KSUGMap\Story;
use TANIOS\Airtable\Airtable;

class TrimAndImportAudio extends Seeder
{
    public function __construct(Airtable $airtable, Guzzle $guzzle)
    {
        $this->airtable = $airtable;
        $this->guzzle = $guzzle;
    }

    public function getSeconds(string $stamp, $log = null)
    {
        $arr = explode('.', $stamp);

        if (count($arr) < 2) {
            return intval($stamp);
        }

        $minutes = intval($arr[0]);
        $seconds = intval(str_pad($arr[1], 2, '0'));

        if (filled($log)) {
            echo sprintf("\nGetting %s time from timestamp %s", $log, $stamp);
            echo sprintf("\n%s seconds: %s", $log, $minutes * 60 + $seconds);
        }

        return $minutes * 60 + $seconds;
    }

    public function processRecords($data)
    {
        $padding = 0;
        $current = 1;

        // $itemsToCheck = collect($data)->filter(function ($item) {
        //     $startStamp = $item->{'checked audio start'};
        //     $startParts = explode('.', $startStamp);

        //     $stopStamp = $item->{'checked audio stop'};
        //     $stopParts = explode('.', $stopStamp);

        //     return 2 == count($startParts) && 1 === strlen($startParts[1]) || 2 == count($stopParts) && 1 === strlen($stopParts[1]);
        // })->each(function ($item) {
        //     echo "\n".$item->{'Place '}.' | '.$item->{'Name'};
        // });

        // dd('');

        foreach ($data as $item) {
            $dom = new DOMDocument();
            $arr = explode('/', $item->{'Link to audio'});

            $id = array_pop($arr);
            $path = sprintf('ksug/final-%s-%s-%s-%s.mp3', Str::slug($item->{'Name'}), $id, trim($item->{'Audio start'}), trim($item->{'Audio stop'}), $padding);

            if (! Storage::disk('s3')->exists($path)) {
                echo sprintf("\n\nProcessing %s of %s", $current, count($data));
                echo "\n".'Grabbing audio src from url...';

                @$dom->loadHTMLFile($item->{'Link to audio'});

                try {
                    $src = $dom->getElementsByTagName('audio')[0]->getAttribute('src');
                    $audio = FFMpeg::create()->open($src);

                    $start = $this->getSeconds($item->{'checked audio start'}, 'start') - $padding;
                    $stop = $this->getSeconds($item->{'checked audio stop'}, 'stop') + $padding;
                    $expectedDuration = $stop - $start;
                    $duration = 0;

                    while ($expectedDuration !== intval($duration)) {
                        Storage::disk('local')->delete(storage_path('app/'.$path));

                        if ($duration > 0) {
                            echo "\n".'Trying again...';
                        }
                        echo "\n".'Trimming audio...';

                        $audio->filters()->clip(TimeCode::fromSeconds($start), TimeCode::fromSeconds($stop - $start));

                        $audio->save(new Mp3(), storage_path('app/'.$path));

                        $ffprobe = FFProbe::create();
                        $duration = intval($ffprobe
                            ->format(storage_path('app/'.$path)) // extracts file informations
                            ->get('duration'));

                        echo sprintf("\nThe duration after clipping is %s. Expected %s. (start %s | stop %s)", $duration, $expectedDuration, $start, $stop);
                    }

                    echo "\n".'Saving to s3...';

                    Storage::disk('s3')->put($path, file_get_contents(storage_path('app/'.$path)), 'public');
                    echo "\n".'Cleaning local disk...';
                    Storage::disk('local')->delete(storage_path('app/'.$path));

                    echo "\n".'Saved audio '.Storage::disk('s3')->url($path);
                    $current++;
                } catch (\Exception $e) {
                    dump($e);
                    echo "\n".'No audio file found for '.$item->{'Link to audio'};
                }
            }

            Story::whereContent(@$item->{'Story'})->update([
                'audio' => Storage::disk('s3')->url($path),
            ]);
        }
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        ini_set('memory_limit', '1G');
        $request = $this->airtable->getContent('Stories');

        do {
            $response = $request->getResponse();

            $data = collect($response['records'])->pluck('fields');
            $this->processRecords($data);
        } while ($request = $response->next());

        echo 'Complete!';
    }
}
