<?php

namespace App\Http\Controllers;

use App\Exports\DataRecordExport;
use App\Http\Resources\DataRecordResource;
use App\Http\Resources\SubcategoryResource;
use App\Http\Resources\SubcategoryVariableResoource;
use App\Models\DataRecord;
use App\Models\SubCategory;
use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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
            'name'=>'required|string|unique:sub_categories,name',
            'description'=>'required|string'
        ]);

        if ($validator->fails()){
            return $this->sendError('validator error',$validator->errors()->all(),400);
        }

        $subcategory = new SubCategory;

        $subcategory->data_category_id = $request->category;
        $subcategory->name = $request->name;
        $subcategory->description= $request->description;

        $subcategory->save();

        return $this->sendResponse(new SubcategoryResource($subcategory),'subcategory created',201);
    }

    public function details($subcategory)
    {
        $subcategory = SubCategory::find($subcategory);
        if (!$subcategory){
            return $this->sendError('not found','subcategory not found',404);
        }

        //$data = $subcategory->data_records->groupBy('batch');


        return $this->sendResponse(new SubcategoryResource($subcategory),'subcategory found',200);
    }

    public function update(Request $request, $subcategory)
    {

        $subcategory = SubCategory::find($subcategory);
        if (!$subcategory){
            return $this->sendError('not found','subcategory not found',404);
        }

        //return $subcategory;
        $validator = Validator::make($request->all(),[
            'name'=>"required|string|unique:sub_categories,name,".$subcategory->id."",
            'description'=>'required|string'
        ]);

        if ($validator->fails()){
            return $this->sendError('validator error',$validator->errors()->all(),400);
        }

        $subcategory->name = $request->name;
        $subcategory->description = $request->description;

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
            "isKey"=>"nullable|in:0,1",
            "firstColumn"=>'required|in:0,1'
        ]);

        if ($validator->fails()){
            return  $this->sendError('validation error',$validator->errors()->all(),400);
        }

        $subcategory->variables()->updateOrCreate([
            "variable_id"=>$request->variable
        ],[
            "variable_id"=>$request->variable,
            "isKey"=>$request->isKey,
            "required"=>$request->required,
            "first_column"=> $request->firstColumn
        ]);

        return $this->sendResponse(new SubcategoryResource($subcategory),'variable added',201);
    }

    public function add_data(Request $request, $subcategory=null)
    {
        $subcategory = SubCategory::find($request->subcategory);

      //  return $subcategory;

        if (!$subcategory){
            return $this->sendError('not found','subcategory not found',404);
        }
        $validator = Validator::make($request->all(),[
            "template"=>"required|mimes:xlsx"
        ]);

        if ($validator->fails()){
            return  $this->sendError('validation error',$validator->errors()->all(),400);
        }

        $excel = Excel::toCollection(null,$request->template)[0];

        //return $excel;
        $upload=[];
        $variables =[];
        for ($i =0;$i< sizeof($excel);$i++){
            if ($i == 0) {
               $variables = $excel[$i];
                continue;
            }


            $upload[]=$excel[$i];

        }
         $subcategoryId = $subcategory->id;
        $validVariables=[];

//          return  Variable::where('name', 'surface dressed')->whereHas('subcategory', function ($query) use ($subcategoryId) {
//            $query->where('subcategory_id', '=','9a2f5ca4-fe72-414f-ae75-3162997db79e');
//        })->get();

        foreach ($variables as $v){

            $response = Variable::select('subcategory_variables.id as subcategory_variable_id')
                ->join('subcategory_variables', 'variables.id', '=', 'subcategory_variables.variable_id')
                ->where('variables.name', $v)
                ->where('subcategory_variables.subcategory_id', $subcategoryId)
                ->first();


           $validVariables[] =$response?->subcategory_variable_id;
        }

//        return $validVariables;
        if (count($validVariables) != count($subcategory->variables)){

            return $this->sendError("validation error","incorrect variables");
        }
        foreach ($upload as $key => $up){
            $batch = rand(00000,99999);
            $i=0;
            foreach ($up as $loopKey => $data){
                $subcategory->data_records()->create([
                    "subcategory_variable_id"=>$validVariables[$i],
                    "data"=>$data,
                    "batch"=>$batch
                ]);
                $i++;
            }

        }


        return $request->template;
    }

    public function download_template($subcategory)
    {
        $subcategory = SubCategory::find($subcategory);

        if (!$subcategory){
            return $this->sendError('not found','subcategory not found',404);
        }
        $resourceCollection =  SubcategoryVariableResoource::collection($subcategory->variables?->sortByDesc('first_column'));
        $heading =[];

        foreach ($resourceCollection as $head){
            $heading[]= $head->variable?->name;
        }

        sort($heading);


        //return  $heading;

        return Excel::download(new DataRecordExport($subcategory,$resourceCollection,$heading),str_replace(" ","_",$subcategory->name,).'.xls');
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
