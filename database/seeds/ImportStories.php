<?php

use Illuminate\Database\Seeder;
use TANIOS\Airtable\Airtable;
use GuzzleHttp\Client as Guzzle;
use KSUGMap\Story;
use KSUGMap\Place;

class ImportStories extends Seeder
{
    public function __construct(Airtable $airtable, Guzzle $guzzle)
    {
        $this->airtable = $airtable;
        $this->guzzle = $guzzle;
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $response = $this->airtable->getContent('Stories')->getResponse();

        $data = collect($response['records'])->pluck('fields');

        $data->map(function ($item) {
            $place = Place::firstOrCreate([
                'name' => @$item->{'Place '},
                'lat' => @$item->{'Latitude'},
                'long' => @$item->{'Longitude'},
                'photo' => @$item->{'photo'},
                'photo_caption' => @$item->{'photo caption '},
            ]);

            return Story::create([
                'day' => @$item->{'Date of Story'},
                'content' => @$item->{'Story'},
                'place_id' => $place->id,
                'subject' => @$item->{'Name'},
                'role' => @$item->{'Role'},
            ]);
        });
    }
}
