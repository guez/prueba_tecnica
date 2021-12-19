<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventDescription;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventDescriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $event_id = $request->get("event_id");
        $event = Event::findOrFail($event_id);

        $eventDescriptions = Event::query()
            ->where('event_id', '=', $event['id'])
            ->get();

        return new JsonResponse([
            "state" => true,
            "code" => 200,
            "event_descriptions" => $eventDescriptions
        ]);
    }

    /**
     * Store a newly created resource in DataBase.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            "event_id"            => "required|max:255",
            "name"            => "required|max:255",
            "language"        => "",
        ]);

        if ($fields['language'] == "") $fields['language'] = "es";

        $eventDescription = EventDescription::query()
            ->where("event_id", '=', $fields['event_id'])
            ->where("language", '=', $fields['language'])
            ->first();

        if ($eventDescription != null) {
            $eventDescription->name = $fields['name'];
            $eventDescription->update();
            $typeEvent = "UPDATE";
            return new JsonResponse([
                "state" => true,
                "code" => 200,
                "event_description" => $eventDescription,
                "type_event" => $typeEvent
            ]);
        }

        $eventDescription = new EventDescription($fields);
        $eventDescription->save();

        return new JsonResponse([
            "state" => true,
            "code" => 202,
            "event_description" => $eventDescription,
            "type_event" =>  "CREATE"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $eventDescription = EventDescription::findOrFail($id);

        return new JsonResponse([
            "state" => true,
            "code" => 200,
            "event_description" => $eventDescription
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $field = $request->validate([
            "name" => "required|max:255",
            "id" => "required|exists:events,id",
            "language" => ""
        ]);
        if ($field['language'] == "") $field['language'] = "es";

        $eventDescription = EventDescription::where('language', $field['language'])->first();
        if($eventDescription == false){
            throw new NotFound();
        }

        $eventDescription->name = $field['name'];
        
        return new JsonResponse([
            "state" => true,
            "code" => 200,
            "event_description" => $eventDescription
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $eventDescription = EventDescription::findOrFail($id);
        $eventDescription->delete();
        
        return new JsonResponse([
            "state" => true,
            "code" => 200,
            "event_description" => $eventDescription
        ]);
    }
}
