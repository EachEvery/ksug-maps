<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use KSUGMap\Place;

class ConvertTiffsToJpegs extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        ini_set('allow_url_fopen', 'on');

        /*
         * We need all the memory to convert these tifs 😩
         */
        ini_set('memory_limit', '1024M');

        Place::where('photo', 'like', '%'.'tif'.'%')->each(function ($placeWithTiffPhoto) {
            $path = collect(explode('/', $placeWithTiffPhoto->photo))->pop();
            $parts = collect(explode('.', $path));
            $parts->pop();

            $newFile = $parts->implode('').'.jpg';

            if (!Storage::disk('s3')->exists('ksug/'.$newFile)) {
                $base64 = base64_encode(file_get_contents($placeWithTiffPhoto->photo));

                Image::make($base64)->save(storage_path('ksug/'.$newFile));

                Storage::disk('s3')->put('ksug/'.$newFile, file_get_contents(storage_path('ksug/'.$newFile)), 'public');
            }

            $placeWithTiffPhoto->update([
                'photo' => Storage::disk('s3')->url('ksug/'.$newFile),
            ]);
        });
    }
}
