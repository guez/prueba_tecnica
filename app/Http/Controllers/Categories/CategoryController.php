<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryDescription;
use Hamcrest\Description;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $qpLanguage = $request->get("language");
        $languages = Category::languages()->get();

        if ($qpLanguage == "") {
            if (Category::haveESLanguage($languages)) {
                $qpLanguage = "es";
            } else {
                if (count($languages) > 0) {
                    $qpLanguage = $languages[0]['language'];
                }
            }
        }

        $categories = Category::description($qpLanguage)->paginate();

        return view('categories.index', [
            "categories" => $categories,
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
        return view('categories.create');
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
            "language"        => "",
        ]);

        DB::beginTransaction();

        $category = Category::where('slug', '=', $field['slug'])->withTrashed()->first();

        if ($category != null) {
            
            if ($category->trashed()) {
                $category->restore();
            }
            $categoryDescription = CategoryDescription::query()
                ->where('category_id', $category['id'])
                ->where('language', $field['language'])
                ->withTrashed()
                ->first();

            if ($categoryDescription != null) {
                DB::commit();
                return $this->update($request, $category['id']);
            }

            $field['category_id'] = $category['id'];
            $categoryDescription = new CategoryDescription($field);
            $categoryDescription->save();
            
            $category['description'] = $categoryDescription;
            DB::commit();
            return new JsonResponse([
                "state" => true,
                "code" => 202,
                "category" => $category
            ]);
        }

        $category = new Category();
        $category->fill($field);
        $category->save();

        $categoryDescription = new CategoryDescription($field);
        $categoryDescription['category_id'] = $category['id'];
        $categoryDescription->save();

        DB::commit();

        $category['descriptions'] = $categoryDescription;

        return new JsonResponse([
            "state" => true,
            "code" => 202,
            "category" => $category
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
        $category = Category::with("category")->findOrFail($id);
        $categoryDescriptions = CategoryDescription::where("category_id", $category['id'])->get();

        return view('categories.detail', [
            "category"                 =>  $category,
            "category_descriptions"    => $categoryDescriptions,
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
        $category = Category::with('descriptions')->findOrFail($id);
        return view('categories.edit', [
            "category"                 =>  $category,
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
            "language"        => "",
        ]);

        if ($field['language'] == "") {
            $field['language'] = "es";
        }

        DB::beginTransaction();
        $category = Category::withTrashed()->findOrFail($id);

        if ($category->trashed()) {
            $category->restore();
        }
        
        $categoryDescription = CategoryDescription::query()
            ->where('category_id', $category['id'])
            ->where('language', $field['language'])
            ->withTrashed()
            ->first();

        if ($categoryDescription == null) {
            $field['category_id'] = $category['id'];
            $categoryDescription = new CategoryDescription($field);
            $categoryDescription->save();
            $category['description'] = $categoryDescription;
            DB::commit();
            return new JsonResponse([
                "state" => true,
                "code" => 202,
                "data" => $category
            ]);
        }
        
        if ($categoryDescription->trashed()) {
            $categoryDescription->restore();
        }

        $categoryDescription->fill($field);
        $categoryDescription['category_id'] = $category['id'];
        $categoryDescription->update();

        DB::commit();

        $category['descriptions'] = $categoryDescription;

        return new JsonResponse([
            "state" => true,
            "code" => 202,
            "data" => $category
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
        $category = Category::findOrFail($id);
        $category->delete();
        $categoryDescriptions = CategoryDescription::where("category_id", $id)->delete();

        return new JsonResponse([
            "state" => true,
            "code" => 202,
            "category" => $category,
            "category_descriptions" => $categoryDescriptions
        ]);
    }
}
