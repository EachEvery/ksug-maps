<?php

use GuzzleHttp\Client as Guzzle;
use Illuminate\Database\Seeder;
use KSUGMap\Place;
use KSUGMap\Story;
use TANIOS\Airtable\Airtable;

class ImportStories extends Seeder
{
    public function __construct(Airtable $airtable, Guzzle $guzzle)
    {
        $this->airtable = $airtable;
        $this->guzzle = $guzzle;
    }

    public function process($data)
    {
        $data->map(function ($item) {
            $hasPhoto = str_contains(@$item->{'photo'}, 'http');

            $latLong = [
                'lat' => @$item->{'Latitude'},
                'long' => @$item->{'Longitude'},
            ];

            $place = Place::firstOrCreate($latLong, [
                'name' => trim(title_case(@$item->{'Place '})),
                'lat' => @$item->{'Latitude'},
                'long' => @$item->{'Longitude'},
                'photo' => $hasPhoto ? @$item->{'photo'} : null,
                'photo_caption' => $hasPhoto ? @$item->{'photo source'} : null,
                'alt_text' => $hasPhoto ? @$item->{'photo caption '} : null,
            ]);

            return Story::create([
                'day' => @$item->{'Date of Story'},
                'content' => @$item->{'Story'},
                'place_id' => $place->id,
                'subject' => title_case(trim(@$item->{'Name'})),
                'full_story_link' => @$item->{'Link to audio'},
                'role' => @$item->{'Role'},
            ]);
        });
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $request = $this->airtable->getContent('Stories');

        do {
            $response = $request->getResponse();
            $data = collect($response['records'])->pluck('fields');
            $this->process($data);
        } while ($request = $response->next());
    }
}
