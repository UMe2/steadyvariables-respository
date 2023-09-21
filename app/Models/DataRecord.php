<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataRecord extends Model
{
    use HasFactory,HasUuids;

    protected $guarded=[];
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'subcategory_id');
    }

    public function subcategory_variable()
    {
        return $this->belongsTo(SubcategoryVariable::class,'subcategory_variable_id');
    }


}
