<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataCategoryResource;
use App\Http\Resources\DataRecordResource;
use App\Http\Resources\KnowledgeResource;
use App\Http\Resources\SubcategoryResource;
use App\Mail\SubscriberMail;
use App\Models\CommonKnowledge;
use App\Models\DataCategory;
use App\Models\SubCategory;
use App\Models\Subscriber;
use App\Services\OperationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{

    public function __construct(private OperationService $operationService)
    {
    }

    public function index(Request $request)
    {

        $subcategories = SubCategory::orderBy("name","asc")->get();
        $categories = DataCategory::all();
        $knowledge = CommonKnowledge::all();
        $topsearch =SubcategoryResource::collection(SubCategory::orderBY('search_count','DESC')->limit(10)->get()) ;


        if (count($knowledge) >10){
            $knowledge= $knowledge->random(10);
        }
        $data=[
            "categories"=>DataCategoryResource::collection($categories),
            "knowledge"=> KnowledgeResource::collection($knowledge),
            "datasets"=> SubcategoryResource::collection($subcategories),
            'topsearch'=>$topsearch
        ];
        return $this->sendResponse($data,'index',200);
    }

    public function subcategories()
    {
        $subcategories = SubCategory::all();

        return $this->sendResponse(SubcategoryResource::collection($subcategories),'list of subcategories',200);
    }

    public function search(Request $request)
    {

        $data=[];
        if (isset($request->search)){
//            if ($request->search !=null)
            $subcategory = SubCategory::where("name",'LIKE',"%{$request->search}%")->get();


            if (count($subcategory) < 1){
                return $this->sendError("not found","data not found",404);
            }
            $data=[
                "data"=>SubcategoryResource::collection($subcategory),
            ];

        }elseif(isset($request->dataCategory)){
            $dataCategory = DataCategory::where("id",$request->dataCategory)->first();


            if (!$dataCategory){
                return $this->sendError("not found","data not found",404);
            }
            if (count($dataCategory->subcategories) < 1){
                return $this->sendError("not found","data not found",404);
            }
            $data=[
                "data"=>SubcategoryResource::collection($dataCategory?->subcategories),
            ];
        }else{
            $subcategory = SubCategory::orderBy('name','asc')->get();

            $data=[
                "data"=>SubcategoryResource::collection($subcategory),
            ];
        }
        return $this->sendResponse($data,"search response",200);
    }

    public function categories()
    {
        $categories = DataCategory::all();

        return $this->sendResponse(DataCategoryResource::collection($categories),'list of categories',200);
    }

    public function subcategory(Request $request,$subcategory)
    {
        $subcategory = SubCategory::find($subcategory);

        if (!$subcategory){
            return $this->sendError("not found","data not found",404);
        }

        if ($request->operation == null){
            $subcategory->search_count+=1;

            $subcategory->update();
        }


        $chartLabel = $subcategory->variables()->where('chart_label',true)->first();

        $chartLabel = $chartLabel?->data_records->pluck('data','batch')->toArray() ;

        $chartData =  $subcategory->variables()->where('chart_data',true)->first();

        $chartData = $chartData?->data_records->pluck('data','batch')->toArray();

            $data = [
                "dataset"=>new SubcategoryResource($subcategory),
                "chartLabel"=>$chartLabel,
                "chartData"=>$chartData
            ];

        return $this->sendResponse($data,"data details",200);
    }

    public function subscribe_newsletter(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'email|required|string'
        ]);

        if ($validator->fails()){
            return $this->sendError('validation error',$validator->errors()->all(),400);
        }

        $subscriber = new Subscriber;

        $subscriber->email = $request->email;

        $subscriber->save();

        Mail::to($subscriber->email)->queue(new SubscriberMail());

        return $this->sendResponse("Subscription success","success",201);


    }

}
