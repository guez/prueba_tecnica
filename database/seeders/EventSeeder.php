<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $this->createEvents([
            "es" => "La Cenicienta",
            "en" => "Cinderella",
            "gl" => "Cenicienta"
        ], 1);

        $this->createEvents([
            "es" => "Risas y rosas",
            "en" => "Laughter and roses",
            "gl" => "Risas e rosas"
        ], 2);

        $this->createEvents([
            "es" => "El Tri",
            "en" => "The Tri",
            "gl" => "O Tri"
        ], 1);

        $this->createEvents([
            "es" => "Luisa Abadía",
            "en" => "Luisa Abadía",
            "gl" => "Luisa Abadía"
        ], 2);
    }

    public function createEvents(array $idioms, int $category_id)
    {
        DB::table('events')->insert([
            'slug' => Str::random((5)),
            'date' => now()->modify("+2 day"),
            'capacity' => rand(10,500),
            'category_id' => $category_id,
        ]);
        $eventId = DB::getPdo()->lastInsertId();

        foreach ($idioms as $idiom => $translatedLanguage) {
            DB::table('event_descriptions')->insert([
                'event_id' => $eventId,
                'language' => $idiom,
                'name' => $translatedLanguage,
            ]);
        }
    }
}
