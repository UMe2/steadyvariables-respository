<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataCategoryResource;
use App\Models\DataCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataCategoryController extends Controller
{
    public function index()
    {
        $category = DataCategory::all();

        return $this->sendResponse( DataCategoryResource::collection($category) ,'data categories',200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|min:3|max:20|unique:data_categories,name',
        ]);

        if ($validator->fails()){
            return $this->sendError('Validation error',$validator->errors()->all(),400);
        }

        $category = new DataCategory;

        $category->name = $request->name;

        $category->save();

        return $this->sendResponse( new DataCategoryResource($category) ,'category created',201);
    }

    public function details($category)
    {
        $category = DataCategory::find($category);

        if (!$category){
            return $this->sendError('not found','category not found',404);
        }

        return $this->sendResponse(new DataCategoryResource($category),'category details',200);
    }

    public function update(Request $request, $category)
    {
        $category = DataCategory::find($category);

        if (!$category){
            return $this->sendError('not found', 'category not found',404);
        }
        $validator = Validator::make($request->all(),[
            'name'=>'required|min:3|max:200|unique:data_categories,name,'.$category->id
        ]);

        $category->name= $request->name;

        $category->update();

        return  $this->sendResponse(new DataCategoryResource($category),'category updated',201);
    }

    public function delete($category)
    {
        $category = DataCategory::find($category);

        if (!$category){
            return $this->sendError('not found','category not found',404);
        }

    }

}
