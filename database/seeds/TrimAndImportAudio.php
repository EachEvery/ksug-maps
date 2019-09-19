<?php

use Illuminate\Database\Seeder;
use TANIOS\Airtable\Airtable;
use GuzzleHttp\Client as Guzzle;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use Illuminate\Support\Facades\Storage;
use KSUGMap\Story;

class TrimAndImportAudio extends Seeder
{
    public function __construct(Airtable $airtable, Guzzle $guzzle)
    {
        $this->airtable = $airtable;
        $this->guzzle = $guzzle;
    }

    public function getSeconds(string $stamp)
    {
        $arr = explode('.', $stamp);

        if (count($arr) < 2) {
            return intval($stamp);
        }

        $minutes = intval($arr[0]);
        $seconds = intval($arr[1]);

        return $minutes * 60 + $seconds;
    }

    public function processRecords($data)
    {
        $padding = 0;

        foreach ($data as $item) {
            $dom = new DOMDocument();
            $arr = explode('/', $item->{'Link to audio'});

            $id = array_pop($arr);
            $path = sprintf('ksug/%s-%s-%s-%s-ffmpeg.mp3', str_slug($item->{'Name'}), $id, trim($item->{'Audio start'}), trim($item->{'Audio stop'}), $padding);

            if (!Storage::disk('s3')->exists($path)) {
                echo "\n".'Grabbing audio src from url...';

                @$dom->loadHTMLFile($item->{'Link to audio'});

                try {
                    $src = $dom->getElementsByTagName('audio')[0]->getAttribute('src');
                    $audio = FFMpeg::create()->open($src);

                    $start = $this->getSeconds($item->{'Audio start'}) - $padding;
                    $stop = $this->getSeconds($item->{'Audio stop'}) + $padding;

                    echo "\n".'Trimming audio...';

                    $audio->filters()->clip(TimeCode::fromSeconds($start), TimeCode::fromSeconds($stop));
                    $audio->save(new Mp3(), storage_path('app/'.$path));

                    echo "\n".'Saving to s3...';
                    Storage::disk('s3')->put($path, file_get_contents(storage_path('app/'.$path)), 'public');
                    echo "\n".'Cleaning local disk...';
                    Storage::disk('local')->delete(storage_path('app/'.$path));

                    echo "\n".'Saved audio '.Storage::disk('s3')->url($path);
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
