<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryDescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'language',
        'name'
    ];

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    public function scopeLanguage($query, $language)
    {
        $query->where('language', $language);
    }    

    /**
     * La categoría tiene asociada a muchas descripciones de categorías.
     */
    public function category()
    {
        return $this->hasMany(App\Models\Category::class);
    }
}
