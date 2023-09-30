<?php

use App\Http\Controllers\CommonKnowledgeController;
use App\Http\Controllers\DataCategoryController;
use App\Http\Controllers\GuestController;
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

Route::get("/{subcategory}/template",[SubCategoryController::class,'download_template'])->name('template');

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
//        Route::patch('/{subcategory}/data',[SubCategoryController::class,'add_data'])
//            ->name('add_data');
        Route::get("/{subcategory}/template",[SubCategoryController::class,'download_template'])->name('template');
        Route::post("/data/upload",[SubCategoryController::class,'add_data'])->name('template.add');
    });

    Route::group(['prefix'=>'variables','as'=>'variable.'],function (){
       Route::get('/',[VariableController::class,'index'])->name('index');
       Route::post('/',[VariableController::class,'create'])->name('create');
       Route::get('/{variable}',[VariableController::class,'details'])->name('view');
       Route::patch('/{variable}',[VariableController::class,'update'])->name('update');
    });

    Route::group(['prefix'=>'knowledge','as'=>'knowledge.'],function (){
       Route::get('/',[CommonKnowledgeController::class,'index'])->name('index');
       Route::post('/',[CommonKnowledgeController::class,'create'])->name('create');
       Route::get('/{knowledge}',[CommonKnowledgeController::class,'details'])->name('details');
       Route::patch('/{knowledge}',[CommonKnowledgeController::class,'update'])->name('update');

    });

});

Route::group(['prefix'=>'guest','as'=>'guest.'],function (){
   Route::get('/index',[GuestController::class,'index'])->name('index');
   Route::post('/index',[GuestController::class,'subscribe'])->name('subscribe');
   Route::get('/search',[GuestController::class,'search'])->name('search');
   Route::get('/subcategories',[GuestController::class,'subcategories'])->name('subcategories');
   Route::get('/subcategories/{subcategory}',[GuestController::class,'subcategory'])->name('subcategory');
   Route::get('/categories',[GuestController::class,'categories'])->name('categories');
});

