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
            $photo = collect([
                'https://nhmisc.s3.us-east-1.amazonaws.com/ksug/fe3295efb6b50e4fcc675af4ed7ea6a6.jpg',
                'https://nhmisc.s3.us-east-1.amazonaws.com/ksug/4e37397989bc96fb201ef6f55ee7327c.jpg',
            ])->random();

            return Story::create([
                'day' => @$item->{'Date of Story'},
                'content' => @$item->{'Story'},
                'location' => @$item->{'Place '},
                'subject_name' => @$item->{'Name'},
                'subject_title' => @$item->{'Role'},
                'lat' => @$item->{'Latitude'},
                'long' => @$item->{'Longitude'},
                'photo' => $photo,
            ]);
        });
    }
}
