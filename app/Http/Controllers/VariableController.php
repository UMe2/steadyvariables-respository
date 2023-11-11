<?php

namespace App\Http\Controllers;

use App\Http\Resources\VariableResource;
use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VariableController extends Controller
{

    public function index()
    {
        $variables = Variable::orderBy('name','ASC')->paginate(20);

        return $this->sendResponse(VariableResource::collection($variables) , 'variable list',200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'name'=>"required|string|max:200|unique:variables,name",
            "alias"=>"nullable|string|max:200"
        ]);

        if ($validator->fails()){
            return $this->sendError('validation error',$validator->errors()->all(),400);

        }

        $variable = new Variable;

        $variable->name= $request->name;
        $variable->alias = $request->alias;
        $variable->save();

        return $this->sendResponse(new VariableResource($variable),"variable create",201);


    }

    public function delete($variable)
    {
        $variable  = Variable::find($variable);

        if (!$variable){
            return $this->sendError('not found','variable not found',404);
        }



        if ($variable->subcategory != null){
            return $this->sendError('validation error','Sorry please the variable has active data on it',400);
        }

        $variable->delete();

        return $this->sendResponse([],'deleted',203);
    }

    public function update(Request $request,$variable)
    {
        $variable  = Variable::find($variable);

        if (!$variable){
            return $this->sendError('not found','variable not found',404);
        }
        $validator = Validator::make($request->all(),[
            'name'=>"required|string|max:200|unique:variables,name,".$variable->id,
            "alias"=>"nullable|string|max:200"
        ]);

        if ($validator->fails()){
            return $this->sendError('validation error',$validator->errors()->all(),400);

        }

        $variable->name= $request->name;
        $variable->alias = $request->alias;
        $variable->update();

        return $this->sendResponse(new VariableResource($variable),"variable updated",201);
    }
}
