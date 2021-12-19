<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'slug',
        'capacity',
        'date'
    ];

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    /**
     * La categoría esta asociada al evento.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * El evento esta asociado a una categoría en su idioma.
     */
    public function categoryDescription()
    {
        return $this->hasOne(categoryDescription::class, 'category_id', 'category_id')->where("language", "category_descriptions.language");
    }


    /**
     * El evento tiene muchas descripciones asociadas a el.
     */
    public function descriptions()
    {
        return $this->hasMany(App\Models\EventDescription::class);
    }

    /**
     * El evento puede tener varios asistentes.
     */
    public function assistants()
    {
        return $this->hasMany(App\Models\EventAssistant::class);
    }

    /**
     * El evento puede tener varios asistentes.
     */
    public function scopeGetCategory($query, $language)
    {
        $subquery = CategoryDescription::query()
            ->select('category_descriptions.name as category_name')
            ->where("category_descriptions.category_id", "events.category_id")
            ->limit(1);
        $query->addSelect(["category_name" => $subquery]);
    }
}
