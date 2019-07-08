<?php

use Illuminate\Database\Seeder;
use TANIOS\Airtable\Airtable;
use GuzzleHttp\Client as Guzzle;
use falahati\PHPMP3\MpegAudio;
use Illuminate\Support\Facades\Storage;

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
        $data = collect($records)->pluck('fields');
        ini_set('memory_limit', '1024M');

        $data->each(function ($item, $i) {
            $dom = new DOMDocument();
            $arr = explode('/', $item->{'Link to audio'});
            $id = array_pop($arr);
            $path = sprintf('audio/%s-%s-%s.mp3', str_slug($item->{'Name'}), $id, $i);

            if (Storage::disk('local')->exists($path)) {
                return;
            }

            @$dom->loadHTMLFile($item->{'Link to audio'});

            try {
                $src = $dom->getElementsByTagName('audio')[0]->getAttribute('src');
                $audioManipulator = MpegAudio::fromFile($src);

                $start = $this->getSeconds($item->{'Audio start'});
                $stop = $this->getSeconds($item->{'Audio stop'});

                $duration = $stop - $start;

                $audioManipulator->trim($start, $duration)->saveFile(storage_path('app/'.$path));
                echo "\n".'Saved audio '.storage_path('app/'.$path);
            } catch (\Exception $e) {
                dump($e);
                echo "\n".'No audio file found for '.$item->{'Link to audio'};
            }
        });
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        do {
            $response = $this->airtable->getContent('Stories')->getResponse();
            $this->processRecords($response['records']);
        } while ($request = $response->next());
    }
}
