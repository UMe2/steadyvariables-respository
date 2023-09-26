<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubcategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = request()->search;
        if (isset($query)){

           $data = SubCategory::where('name','LIKE',"%".$query."%")->get();

           return SubcategoryResource::collection($data);



        }

    }
}
