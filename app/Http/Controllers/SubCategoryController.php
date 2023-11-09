<?php

namespace App\Http\Controllers;

use App\Exports\DataRecordExport;
use App\Http\Resources\DataRecordResource;
use App\Http\Resources\SubcategoryResource;
use App\Http\Resources\SubcategoryVariableResoource;
use App\Models\DataRecord;
use App\Models\SubCategory;
use App\Models\SubcategoryVariable;
use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'description'=>'required|string',
            'variables.*'=>'required',
            'operations'=>'nullable'
        ]);

        if ($validator->fails()){
            return $this->sendError('validator error',$validator->errors()->all(),400);
        }

        $subcategory = new SubCategory;
        DB::transaction(function () use ($request,$subcategory){


            $subcategory->data_category_id = $request->category;
            $subcategory->name = $request->name;
            $subcategory->description= $request->description;

            $subcategory->save();

            foreach ($request->variables as $variable){
                $subcategory->variables()->updateOrCreate([
                    "variable_id"=>$variable['variable']
                ],[
                    "variable_id"=>$variable['variable'],
                    "first_column"=> $variable['firstColumn'],
                    'chart_data'=>$variable['chartData'],
                    'chart_label'=>$variable['chartLabel']
                ]);
            }




        });

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
            'variables'=>'required',
        ]);

        if ($validator->fails()){
            return  $this->sendError('validation error',$validator->errors()->all(),400);
        }


        foreach ($request->variables as $variable){
            $subcategory->variables()->updateOrCreate([
                "variable_id"=>$variable['variable']
            ],[
                "variable_id"=>$variable['variable'],
                "first_column"=> $variable['firstColumn'],
                'chart_data'=>$variable['chartData'],
                'chart_label'=>$variable['chartLabel']
            ]);
        }

        return $this->sendResponse(new SubcategoryResource($subcategory),'variable added',201);
    }
    public function add_operation(Request $request,$subcategory)
    {
        $subcategory = SubCategory::find($subcategory);

        if (!$subcategory){
            return $this->sendError('not found','subcategory not found',404);
        }

        $validator = Validator::make($request->all(),[
            'operation'=>'required|exists:operations,id|uuid',
            "variable"=>"nullable|exists:variables,id|uuid",
        ]);

        if ($validator->fails()){
            return  $this->sendError('validation error',$validator->errors()->all(),400);
        }

        $variable = $subcategory->variables->where('variable_id',$request->variable)->first();

        if (!$variable){
            return $this->sendError('not found',"variable selected not part of the dataset variable",400);
        }


        $subcategory->operations()->updateOrCreate([
            "operation_id"=>$request->operation
        ],[
            "operation_id"=>$request->operation,
            "variable_id"=>$request->variable,
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
        return $upload;
        $variables = $variables->filter(function ($value) {
            return $value !== null;
        });

//        $upload = array_filter($upload, function ($value) {
//            return $value !== null;
//        });

        // return $upload;
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


            $validVariables[] =$response->subcategory_variable_id;
        }

        // return $validVariables;


        if (count($validVariables) != count($subcategory->variables)){

            return $this->sendError("validation error","incorrect variables");
        }

        $cat =[];
        //return $upload;
        foreach ($upload as $key => $up){
            $batch = rand(00000,99999);

            $i=0;

            foreach ($up as $loopKey => $data){
                if (!isset($data)){
                    continue;
                }

                $exists = $subcategory->data_records()
                    ->where('subcategory_variable_id',$validVariables[$i])
                    ->where('data',$data);

                if ($exists){
                    continue;
                }
                $subcategory->data_records()->create([
                    "subcategory_variable_id"=>$validVariables[$i],
                    "data"=>$data,
                    "batch"=>$batch
                ]);

                $i++;
            }

            //return $cat;

        }


        return $this->sendResponse(new  SubcategoryResource($subcategory),'data added',201);
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

        $excel = Excel::download(new DataRecordExport($subcategory,$resourceCollection,$heading),str_replace(" ","_",$subcategory->name,).'.xlsx');

        $content = $excel->getFile()->getContent();

        // Set the appropriate headers for a Blob response
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . str_replace(" ", "_", $subcategory->name) . '.xls"',
        ];

        return response($content, 200, $headers);
    }
    public function delete($subcategory)
    {
        $subcategory = SubCategory::find($subcategory);
        if (!$subcategory){
            return $this->sendError('not found','subcategory not found',404);
        }

        $subcategory->data_records()->delete();
        $subcategory->variables()->delete();

       $subcategory = $subcategory->delete();

        return $this->sendResponse($subcategory,'subcategory deleted',200);
    }

    public function remove_variable($variableId)
    {
        $variable = SubcategoryVariable::find($variableId);

        $variable->data_records()->delete();

        $variable->delete();

        return $this->sendResponse(null,'variabale removed',200);



    }


}
