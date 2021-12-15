<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = ['slug'];

    protected $dates = ['deleted_at'];
    
    public $timestamps = true;

    /**
     * La categoría tiene una o muchas descripciones.
     */
    public function descriptions()
    {
        return $this->hasMany(CategoryDescription::class, 'category_id','id');
    }
}
