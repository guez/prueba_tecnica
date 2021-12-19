<?php

namespace App\Http\Controllers\Events;

use App\Exceptions\DisponibilityException;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessEmailAssistants;
use App\Models\Event;
use App\Models\EventAssistant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EventAssistantsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            "amount"        => "required|numeric|min:1",
            "event_id"      => "required|exists:events,id",
            "email"         => "required|email",
        ]);
        
        $amount =$field['amount'];
        
        $event = Event::query()
            ->addSelect('events.id', 'events.capacity', DB::raw('count(event_assistants.id) as assistant'))
            ->leftJoin('event_assistants', function($query){
                return $query->on('events.id','=','event_assistants.event_id');
            })
            ->where('events.id', '=', $field['event_id'])
            ->groupBy('events.id','events.capacity')
            ->first();
        
        $assistant = $event['assistant'];
        $disponibility = $event['capacity'] - $assistant;

        if($disponibility < $amount){
            throw new DisponibilityException();
        }

        $event = $this->getEvent($field);
        dispatch_sync(new ProcessEmailAssistants([
            "event_description"=>$event['event_description'],
            "email"=>$event['email'],
            "slug"=>$event['slug'],
            "date"=>$event['date'],
            "category_description"=>$event['category_description'],
        ], $field['amount']));



        $data = [];
        for ($i=0; $i < $field['amount']; $i++) { 
            $data[] = [
                'event_id'=>$field['event_id'], 
                'email'=> $field['email']
            ];
        }
        
        EventAssistant::insert($data); 

        return new JsonResponse([
            "state" => true,
            "code" => 202 
        ]);
    }


    private function getEvent($field){ 
        

        $language = 'es';
        $event = Event::query()
            ->select([
                'events.id',
                'events.slug',
                'events.date',
                'event_descriptions.name as event_description',
                'category_descriptions.name as category_description',
            ])
            ->join('event_descriptions', 'event_descriptions.event_id', '=', 'events.id')
            ->leftJoin('categories', 'events.category_id', '=', 'categories.id')
            ->leftJoin('category_descriptions', function ($join) use ($language) {
                $join->on('category_descriptions.category_id', '=', 'categories.id')
                    ->where('category_descriptions.language', '=', $language);
            })
            ->leftJoin('event_assistants', 'event_assistants.event_id', '=', 'events.id')
            ->where('event_descriptions.language', '=', $language)
            ->where('events.id', '=', $field['event_id'])
            ->groupBy(
                'id',
                'slug',
                'date',
                'event_description',
                'category_description'
            )
            ->first();
        if ($event == null) {
            $event = Event::query()
                ->select([
                    'events.id',
                    'events.slug',
                    'events.date',
                    'event_descriptions.name as event_description',
                    'category_descriptions.name as category_description',
                ])
                ->leftJoin('event_descriptions', function ($join) use ($language) {
                    $join->on('event_descriptions.event_id', '=', 'events.id');
                })
                ->leftJoin('categories', 'events.category_id', '=', 'categories.id')
                ->leftJoin('category_descriptions', function ($join) use ($language) {
                    $join->on('category_descriptions.category_id', '=', 'categories.id');
                })
                ->leftJoin('event_assistants', 'event_assistants.event_id', '=', 'events.id')
                ->where('events.id', '=', $field['event_id'])
                ->groupBy(
                    'id',
                    'slug',
                    'date',
                    'event_description',
                    'category_description'
                )
                ->first();
        }
        return $event;
    }

}
