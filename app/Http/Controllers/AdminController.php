<?php

namespace App\Http\Controllers;

use App\Models\CommonKnowledge;
use App\Models\DataCategory;
use App\Models\SubCategory;
use App\Models\Subscriber;
use App\Models\Variable;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $variables = Variable::all()->count();
        $datasets = SubCategory::all()->count();
        $category = DataCategory::all()->count();
        $subscribers = Subscriber::all()->count();
        $commonKnowledge = CommonKnowledge::all()->count();

        $data=[
           "variables" =>$variables,
            "datasets"=>$datasets,
            'category'=>$category,
            'subscribers'=>$subscribers,
            'commonKnowledge'=> $commonKnowledge
        ];

        return $this->sendResponse($data,'dashboard',200);
    }
}
