<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventDescription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventDescriptionController extends Controller
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
            "status" => true,
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

            return new Jsonresponse([
                "status" => true,
                "code" => 200,
                "event_description" => $eventDescription,
                "type_event" => "UPDATE"
            ]);
        }

        $eventDescription = new EventDescription($fields);
        $eventDescription->save();

        return new Jsonresponse([
            "status" => true,
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
            "status" => true,
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
        return new JsonResponse($request->all());
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
        return new Jsonresponse([
            "status" => true,
            "code" => 200,
            "event_description" => $eventDescription
        ]);
    }
}
