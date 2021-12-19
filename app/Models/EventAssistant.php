<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAssistant extends Model
{
    use HasFactory, SoftDeletes;

    
    protected $fillable = [
        'event_id',
        'email'
    ];
    
    protected $dates = ['deleted_at'];

    public $timestamps = true;

    
    /**
     * El evento puede tener asistentes.
     */
    public function events()
    {
        return $this->hasMany(App\Models\Event::class);
    }


}
