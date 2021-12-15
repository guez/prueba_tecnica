<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventDescription extends Model
{
    use HasFactory, SoftDeletes;

    
    protected $fillable = [
        'event_id',
        'language',
        'name'
    ];


    protected $dates = ['deleted_at'];

    public $timestamps = true;

    
    /**
     * El evento tiene muchas descripciones asociadas a el.
     */
    public function events()
    {
        return $this->hasMany(App\Models\Event::class);
    }


}
