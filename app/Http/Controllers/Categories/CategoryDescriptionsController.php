<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryDescription;
use App\Models\EventDescription;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryDescriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category_id = $request->get("category_id");
        $category = Category::findOrFail($category_id);

        $categoryDescriptions = CategoryDescription::query()
            ->where('category_id', '=', $category['id'])
            ->get();

        return new JsonResponse([
            "state" => true,
            "code" => 200,
            "category_descriptions" => $categoryDescriptions
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
        $fields = $request->validate([
            "category_id"     => "required|max:255",
            "name"            => "required|max:255",
            "language"        => "",
        ]);

        if ($fields['language'] == "") $fields['language'] = "es";

        $categoryDescription = CategoryDescription::query()
            ->where("category_id", '=', $fields['category_id'])
            ->where("language", '=', $fields['language'])
            ->withTrashed()
            ->first();

        if ($categoryDescription != null) {
            $categoryDescription->name = $fields['name'];
            $categoryDescription->update();

            $typeEvent = "UPDATE";

            if ($categoryDescription->trashed()) {
                $categoryDescription->restore();
                $typeEvent = "RESTORE";
            }
            return new Jsonresponse([
                "state" => true,
                "code" => 200,
                "category_description" => $categoryDescription,
                "type_event" => $typeEvent
            ]);
        }

        $categoryDescription = new CategoryDescription($fields);
        $categoryDescription->save();

        return new Jsonresponse([
            "state" => true,
            "code" => 202,
            "category_description" => $categoryDescription,
            "type_event" =>  "CREATE"
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
        $categoryDescription = CategoryDescription::findOrFail($id);

        return new JsonResponse([
            "state" => true,
            "code" => 200,
            "category_description" => $categoryDescription
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
            "name" => "required|max:255",
            "language" => ""
        ]);
        if ($field['language'] == "") $field['language'] = "es";
        $category = Category::findOrFail($id);

        $categoryDescription = CategoryDescription::where("category_id", $category['id'])->where('language', $field['language'])->first();
        if($categoryDescription == false){
            throw new NotFound();
        }

        $categoryDescription['name'] = $field['name'];
        $categoryDescription->update();
        
        return new Jsonresponse([
            "state" => true,
            "code" => 200,
            "category_description" => $categoryDescription
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
        $categoryDescription = CategoryDescription::findOrFail($id);
        $category = Category::withCount("descriptions")->findOrFail($categoryDescription['category_id']);
        if($category['descriptions_count']>1){
            $categoryDescription->delete();
        }else{
            $categoryDescription->delete();
            $category->delete();
        }

        return new Jsonresponse([
            "state" => true,
            "code" => 200,
            "category_description" => $categoryDescription,
            "category" => $category
        ]);
    }
}
