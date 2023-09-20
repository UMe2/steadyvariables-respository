<?php

use App\Http\Controllers\DataCategoryController;
use App\Models\DataCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware'=>'auth:sanctum','prefix'=>'admin','as'=>'admin.'],function (){

    Route::group(['prefix'=>'data-category','as'=>'category.'],function (){
       Route::get('/',[DataCategoryController::class,'index'])->name('index');
       Route::post('/',[DataCategoryController::class,'create'])->name('create');
       Route::get('/{category}',[DataCategoryController::class,'details'])->name('view');
       Route::patch('/{category}',[DataCategoryController::class,'update'])->name('update');
    });
});

