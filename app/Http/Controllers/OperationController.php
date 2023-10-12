<?php

namespace App\Http\Controllers;

use App\Http\Resources\OperationResource;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperationController extends Controller
{


    public function index()
    {
        $operations = Operation::all();

        return $this->sendResponse(OperationResource::collection($operations),'list of operations',200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'name'=>'required|unique:operations,name|string',
        ]);

        if ($validator->fails()){
            return $this->sendError("validation error",$validator->errors()->all(),400);
        }

        $operation = new Operation;

        $operation->name = $request->name;

        $operation->save();

        return $this->sendResponse(new OperationResource($operation),'operation created',201);
    }
    public function update(Request $request,$operation)
    {
        $operation = Operation::find($operation);

        if (!$operation){
            return $this->sendError('not found','operation not found',404);
        }

        $validator = Validator::make($request->all(),[
           'name'=>"required|string|unique:operations,name,".$operation->id."",
        ]);

        if ($validator->fails()){
            return $this->sendError("validation error",$validator->errors()->all(),400);
        }


        $operation->name = $request->name;

        $operation->update();

        return $this->sendResponse(new OperationResource($operation),'operation updated',201);
    }
}
