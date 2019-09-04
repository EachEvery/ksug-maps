<?php

use Illuminate\Database\Seeder;
use TANIOS\Airtable\Airtable;
use GuzzleHttp\Client as Guzzle;
use KSUGMap\Story;

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
            return Story::create([
                'day' => @$item->{'Date of Story'},
                'content' => @$item->{'Story'},
                'location' => @$item->{'Place '},
                'subject' => @$item->{'Name'},
                'role' => collect(['KSU Student', 'Resident', 'National Guard', 'High School Student', 'KSU Staff', 'KSU Faculty'])->random(),
                'lat' => @$item->{'Latitude'},
                'long' => @$item->{'Longitude'},
                'photo' => @$item->{'photo'},
                'photo_caption' => @$item->{'photo caption '},
            ]);
        });
    }
}
