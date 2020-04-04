<?php

namespace KSUGMap\Console\Commands;

use Illuminate\Console\Command;
use KSUGMap\Place;
use KSUGMap\Story;

class MigrateToMediaLibrary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ksug:migrate-to-media-library';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move place photos to the photo gallary infrustructure.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function alreadyHasMedia($model, $url, $collection)
    {
        $filename = array_last(explode('/', $url));

        $matches = $model->getMedia($collection)
            ->filter(function ($media) use ($filename) {
                return trim($media->file_name) === trim($filename);
            });

        return $matches->count() !== 0;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $places = Place::whereNotNull('photo')->get();

        $places->each(function ($place, $i) use ($places) {
            $this->info(sprintf('%s/%s Adding photo to media library...', $i + 1, $places->count()));

            if (trim($place->photo) === '') {
                $this->info(sprintf('%s/%s Empty file, skipping!', $i + 1, $places->count()));

                return;
            }

            if ($this->alreadyHasMedia($place, $place->photo, 'place_photos')) {
                $this->info(sprintf('%s/%s File already exists, skipping!', $i + 1, $places->count()));

                return;
            }

            $protocol = '';

            if (! str_contains($place->photo, ':')) {
                $protocol = 'https:';
            }

            $place->addMediaFromUrl($protocol.$place->photo)
                ->withCustomProperties([
                    'alt_text' => $place->alt_text,
                    'photo_caption' => $place->photo_caption,
                ])->toMediaCollection('place_photos');

            $this->info(sprintf('%s/%s Finished!', $i + 1, $places->count()));
        });

        $this->info('Finished adding photos to media library.');

        $stories = Story::all();

        $stories->each(function ($story, $i) use ($stories) {
            $this->info(sprintf('%s/%s Adding audio to media library...', $i + 1, $stories->count()));

            if (trim($story->audio) === '') {
                $this->info(sprintf('%s/%s Empty file, skipping!', $i + 1, $stories->count()));

                return;
            }

            if ($this->alreadyHasMedia($story, $story->audio, 'story_audio')) {
                $this->info(sprintf('%s/%s File already exists, skipping.', $i + 1, $stories->count()));

                return;
            }

            $protocol = '';

            if (! str_contains($story->audio, ':')) {
                $protocol = 'https:';
            }

            $story->addMediaFromUrl($protocol.$story->audio)->toMediaCollection('story_audio');

            $this->info(sprintf('%s/%s Finished!', $i + 1, $stories->count()));
        });
    }
}
