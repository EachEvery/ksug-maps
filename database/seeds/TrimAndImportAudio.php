<?php

use Illuminate\Database\Seeder;
use TANIOS\Airtable\Airtable;
use GuzzleHttp\Client as Guzzle;
use falahati\PHPMP3\MpegAudio;
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

    public function processRecords($records)
    {
        $data = collect($records)->pluck('fields')->toArray();
        $padding = 8;
        ini_set('memory_limit', '1G');

        foreach ($data as $item) {
            $dom = new DOMDocument();
            $arr = explode('/', $item->{'Link to audio'});

            $id = array_pop($arr);
            $path = sprintf('ksug/%s-%s-%s-%s-padded-%s.mp3', str_slug($item->{'Name'}), $id, trim($item->{'Audio start'}), trim($item->{'Audio stop'}), $padding);

            if (!Storage::disk('s3')->exists($path)) {
                echo "\n".'Grabbing audio src from url...';

                @$dom->loadHTMLFile($item->{'Link to audio'});

                try {
                    $src = $dom->getElementsByTagName('audio')[0]->getAttribute('src');
                    $audioManipulator = MpegAudio::fromFile($src);

                    $start = $this->getSeconds($item->{'Audio start'}) - $padding;
                    $stop = $this->getSeconds($item->{'Audio stop'}) + $padding;

                    $duration = $stop - $start;

                    echo "\n".'Trimming audio...';
                    $audioManipulator->trim($start, $duration)->saveFile(storage_path('app/'.$path));
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

        echo 'Complete!';
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $response = $this->airtable->getContent('Stories')->getResponse();
        $this->processRecords($response['records']);
    }
}
