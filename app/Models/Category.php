<?php

namespace App\Models;

use Hamcrest\Description;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'id',
        'slug'
    ];

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    /**
     * La categoría tiene una o muchas descripciones.
     */
    public function descriptions()
    {
        return $this->hasMany(CategoryDescription::class);
    }

    /**
     * La categoría tiene una descripción .
     */
    public function scopeDescription($query, $language)
    {
        $query->select(["categories.id", "categories.slug", "category_descriptions.language", "category_descriptions.name"])->join("category_descriptions", 'category_descriptions.category_id', 'categories.id')
            ->whereNull("category_descriptions.deleted_at")
            ->where('category_descriptions.language', $language);
    }

    /**
     * Extraer Lenguajes .
     */
    public function scopeLanguages($query)
    {
        $query->select('language')->join("category_descriptions", 'category_descriptions.category_id', 'categories.id')->whereNull("category_descriptions.deleted_at")->distinct("category_descriptions.languages");
    }


    /**
     * Extraer Lenguajes .
     */
    public static function haveESLanguage($languages): bool
    {
        foreach ($languages as $language) {
            if ($language['language'] == "es") {
                return true;
            }
        }
        return false;
    }
}
