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
}
