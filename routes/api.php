<?php

use App\Http\Controllers\DataCategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\VariableController;
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


    Route::group(['prefix'=>'subcategories','as'=>'subcategory.'],function (){
        Route::get('/',[SubCategoryController::class,'index'])->name('index');
        Route::post('/',[SubCategoryController::class,'create'])->name('create');
        Route::get('/{subcategory}',[SubCategoryController::class,'details'])->name('view');
        Route::patch('/{subcategory}',[SubCategoryController::class,'update'])->name('update');
        Route::patch('/{subcategory}/variable',[SubCategoryController::class,'add_variable'])->name('add_variable');
    });

    Route::group(['prefix'=>'variables','as'=>'variable.'],function (){
       Route::get('/',[VariableController::class,'index'])->name('index');
       Route::post('/',[VariableController::class,'create'])->name('create');
       Route::get('/{variable}',[VariableController::class,'details'])->name('view');
       Route::patch('/{variable}',[VariableController::class,'update'])->name('update');
    });



});

