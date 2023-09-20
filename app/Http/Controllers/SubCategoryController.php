<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubcategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{

    public function index()
    {
        $subcategories = SubCategory::orderBy('name','asc')->paginate(20);

        return $this->sendResponse(SubcategoryResource::collection($subcategories),'list of subcategories',200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category'=>'required|uuid|exists:data_categories,id',
            'name'=>'required|string|unique:sub_categories,name'
        ]);

        if ($validator->fails()){
            return $this->sendError('validator error',$validator->errors()->all(),400);
        }

        $subcategory = new SubCategory;

        $subcategory->data_category_id = $request->category;
        $subcategory->name = $request->name;

        $subcategory->save();

        return $this->sendResponse(new SubcategoryResource($subcategory),'subcategory created',201);
    }

    public function details($subcategory)
    {
        $subcategory = SubCategory::find($subcategory);
        if (!$subcategory){
            return $this->sendError('not found','subcategory not found',404);
        }

        return $this->sendResponse(new SubcategoryResource($subcategory),'subcategory found',200);
    }

    public function update(Request $request, $subcategory)
    {
        $subcategory =  SubCategory::find($subcategory);

        $validator = Validator::make($request->all(),[
            'name'=>'required|string|unique:sub_categories,name,'.$subcategory->id
        ]);

        if ($validator->fails()){
            return $this->sendError('validator error',$validator->errors()->all(),400);
        }




        $subcategory->name = $request->name;

        $subcategory->update();

        return $this->sendResponse(new SubcategoryResource($subcategory),
            'subcategory updated',201);

    }

    public function add_variable(Request $request,$subcategory)
    {
        $subcategory = SubCategory::find($subcategory);

        if (!$subcategory){
            return $this->sendError('not found','subcategory not found',404);
        }

        $validator = Validator::make($request->all(),[
            'variable'=>'required|exists:variables,id|uuid',
            "required"=>"nullable|in:1,0",
            "isKey"=>"nullable|in:0,1"
        ]);

        if ($validator->fails()){
            return  $this->sendError('validation error',$validator->errors()->all(),400);
        }

        $subcategory->variables()->updateOrCreate([
            "variable_id"=>$request->variable
        ],[
            "variable_id"=>$request->variable,
            "isKey"=>$request->isKey,
            "required"=>$request->required
        ]);

        return $this->sendResponse(new SubcategoryResource($subcategory),'variable added',201);
    }
    public function delete($subcategory)
    {
        $subcategory = SubCategory::find($subcategory);
        if (!$subcategory){
            return $this->sendError('not found','subcategory not found',404);
        }

       $subcategory = $subcategory->delete();

        return $this->sendResponse($subcategory,'subcategory deleted',200);
    }
}
