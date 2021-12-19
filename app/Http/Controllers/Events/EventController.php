<?php

namespace App\Http\Controllers\Events;

use App\Exceptions\DisponibilityException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryDescription;
use App\Models\Event;
use App\Models\EventAssistant;
use App\Models\EventDescription;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $qpLanguage = $request->get("language");
        if ($qpLanguage == "") {
            $qpLanguage = "es";
        }

        $events = Event::query()
            ->select([
                'events.id',
                'categories.id as category_id',
                'category_descriptions.name as category_name',
                'event_descriptions.name as event_description',
                'events.capacity',
                DB::raw('count(event_assistants.id) as assistants_amount')
            ])
            ->join('event_descriptions', 'event_descriptions.event_id', '=', 'events.id')
            ->leftJoin('categories', 'events.category_id', '=', 'categories.id')
            ->leftJoin('category_descriptions', function ($join) use ($qpLanguage) {
                $join->on('category_descriptions.category_id', '=', 'categories.id')
                    ->where('category_descriptions.language', '=', $qpLanguage);
            })
            ->leftJoin('event_assistants', 'event_assistants.event_id', '=', 'events.id')
            ->where('event_descriptions.language', '=', $qpLanguage)
            ->groupBy(
                'id',
                'category_id',
                'category_name',
                'event_description',
                'capacity'
            )
            ->get();

        $languages = Event::query()
            ->join("event_descriptions", 'events.id','event_descriptions.event_id')
            ->select('language')->distinct()->get();
        return view('events.index', [
            "events" => $events,
            "languages" => $languages,
            "languageSelected" => $qpLanguage
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::with('descriptions')->get();
        return view('events.create', [
            "categories" => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            "slug"            => "required|max:255",
            "name"            => "required|max:255",
            "capacity"        => "required|min:1",
            "date"           => "required|date|after:start_date",
            "language"        => "",
            "category_id"     => "required|exists:categories,id",
        ]);
        if ($field['language'] == "") {
            $field['language'] = "es";
        }

        DB::beginTransaction();
        $event = Event::where('events.slug', '=', $field['slug'])->first();

        if ($event != null) {
            $eventDescription = EventDescription::query()
                ->where('event_id', $event['id'])
                ->where('language', $field['language'])
                ->first();

            if ($eventDescription != null) {
                DB::commit();
                return $this->update($request, $event['id']);
            }

            $field['event_id'] = $event['id'];
            $eventDescription = new EventDescription($field);
            $eventDescription->save();
            $event['description'] = $eventDescription;
            DB::commit();
            return new JsonResponse([
                "state" => true,
                "code" => 202,
                "data" => $event
            ]);
        }

        $event = new Event();
        $event->fill($field);
        $event->save();

        $eventDescription = new EventDescription($field);
        $eventDescription['event_id'] = $event['id'];
        $eventDescription->save();

        DB::commit();

        $event['descriptions'] = $eventDescription;

        return new JsonResponse([
            "state" => true,
            "code" => 202,
            "data" => $event
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::getCategory("es")->findOrFail($id);
        $eventDescriptions = EventDescription::where("event_id", $event['id'])->get();
        return view('events.detail', [
            "event"                 =>  $event,
            "event_descriptions"    => $eventDescriptions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // throw new \Exception("sd");
        $event = Event::findOrFail($id);
        $eventDescriptions = EventDescription::where("event_id", $event['id'])->get();
        $categories = Category::with('descriptions')->get();

        return view('events.edit', [
            "event"                 =>  $event,
            "event_descriptions"    => $eventDescriptions,
            "categories"            => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $field = $request->validate([
            "slug"            => "required|max:255",
            "name"            => "required|max:255",
            "capacity"        => "required|min:1",
            "date"           => "required|date|after:start_date",
            "language"        => "",
            "category_id"     => "required|exists:categories,id",
        ]);

        if ($field['language'] == "") {
            $field['language'] = "es";
        }

        DB::beginTransaction();
        $event = Event::findOrFail($id);
        
        $event->query()
            ->addSelect('events.id', 'events.capacity', DB::raw('count(event_assistants.id) as assistant'))
            ->leftJoin('event_assistants', function($query){
                return $query->on('events.id','=','event_assistants.event_id');
            })
            ->where('events.id', '=', $field['event_id'])
            ->groupBy('events.id','events.capacity')
            ->first();
        
        $assistant = $event['assistant'];
        $disponibility = $event['capacity'] - $assistant;


        $event->fill($field);
        $event->update();

        $eventDescription = EventDescription::query()
            ->where('event_id', $event['id'])
            ->where('language', $field['language'])
            ->first();

        if ($eventDescription == null) {
            $field['event_id'] = $event['id'];
            $eventDescription = new EventDescription($field);
            $eventDescription->save();
            $event['description'] = $eventDescription;
            DB::commit();
            return new JsonResponse([
                "state" => true,
                "code" => 202,
                "data" => $event
            ]);
        }
        $eventDescription->fill($field);
        $eventDescription['event_id'] = $event['id'];
        $eventDescription->update();

        DB::commit();

        $event['descriptions'] = $eventDescription;

        return new JsonResponse([
            "state" => true,
            "code" => 202,
            "data" => $event
        ]);
    }


    /**
     * Update Partially the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePartially(Request $request, $id)
    {
        $field = $request->validate([
            "slug"              => "max:255",
            "capacity"          => "min:1",
            "date"              => "date|after:start_date",
            "category_id"       => "exists:categories,id",
        ]);

        DB::beginTransaction();
        $event = Event::findOrFail($id);
        if ($field['category_id'] != "")    $event['category_id'] = $field['category_id'];
        if ($field['slug'] != "")           $event['slug'] =        $field['slug'];
        if ($field['date'] != "")           $event['date'] =        $field['date'];
        
        if ($field['capacity'] != ""){
            if($event['capacity'] != $field['capacity']){
                $eventAssistant = EventAssistant::where('event_id', '=', $event['id'])->count();
                if($eventAssistant > $field['capacity']){
                    throw new DisponibilityException();
                }
                $event['capacity'] = $field['capacity'];
            }
                
        }

        $event->update();
        DB::commit();

        return new JsonResponse([
            "state" => true,
            "code" => 202,
            "event" => $event
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        
        return new JsonResponse([
            "state" => true,
            "code" => 202,
            "event" => $event
        ]);
    }
}
