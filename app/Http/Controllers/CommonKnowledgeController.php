<?php

namespace App\Http\Controllers;

use App\Http\Resources\KnowledgeResource;
use App\Models\CommonKnowledge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommonKnowledgeController extends Controller
{

    public function index()
    {
        $knowledges = CommonKnowledge::orderBy('created_at','desc')->get();

        return $this->sendResponse(KnowledgeResource::collection($knowledges),'list of knowledge',200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'title'=>"required|string|max:200",
           "message"=>"required|string"
        ]);

        if ($validator->fails()){
            return $this->sendError("validation error",$validator->errors()->all(),400);
        }

        $knowledge = new CommonKnowledge;

        $knowledge->title = $request->title;
        $knowledge->message= $request->message;

        $knowledge->save();

        return $this->sendResponse(new KnowledgeResource($knowledge),"common knowlodge created",201);
    }
    public function update(Request $request,$knowledge)
    {
        $knowledge =  CommonKnowledge::find($knowledge);

        if (!$knowledge){
            return $this->sendError("not found","common knowledge not found",404);
        }
        $validator = Validator::make($request->all(),[
           'title'=>"required|string|max:200",
           "message"=>"required|string"
        ]);

        if ($validator->fails()){
            return $this->sendError("validation error",$validator->errors()->all(),400);
        }



        $knowledge->title = $request->title;
        $knowledge->message= $request->message;

        $knowledge->update();

        return $this->sendResponse(new KnowledgeResource($knowledge),"common knowlodge updated",201);
    }

    public function details($knowledge)
    {
        $knowledge = CommonKnowledge::find($knowledge);

        if (!$knowledge){
            return $this->sendError('not found',"Knowledge not found",404);
        }

        return  $this->sendResponse(new KnowledgeResource($knowledge),'knowledge found',200);
    }

    public function delete($knowledge)
    {
        $knowledge =  CommonKnowledge::find($knowledge);

        if (!$knowledge){
            return $this->sendError("not found","common knowledge not found",404);
        }

        $knowledge->delete();

        return $this->sendResponse([],'deleted',203);
    }


}
