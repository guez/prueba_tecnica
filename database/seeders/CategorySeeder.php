<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $this->createCategory([
            "es" => "Obra de teatro",
            "en" => "play",
            "gl" => "xogar"
        ]);

        $this->createCategory([
            "es" => "Concierto",
            "en" => "Concert",
            "gl" => "Concerto"
        ]);
    }

    public function createCategory(array $idioms)
    {
        DB::table('categories')->insert([
            'slug' => Str::random((5)),
        ]);
        $categoryId = DB::getPdo()->lastInsertId();

        foreach ($idioms as $idiom => $translatedLanguage) {
            
            DB::table('category_descriptions')->insert([
                'category_id' => $categoryId,
                'language' => $idiom,
                'name' => $translatedLanguage,
            ]);
        }
    }
    
}
