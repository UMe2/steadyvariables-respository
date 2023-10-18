<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory,HasUuids;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function category()
    {
        return $this->belongsTo(DataCategory::class,'data_category_id');
    }

    public function variables()
    {
        return $this->hasMany(SubcategoryVariable::class,'subcategory_id');
    }

    public function data_records()
    {
        return $this->hasMany(DataRecord::class,'subcategory_id');
    }

    public function operations()
    {
        return $this->hasMany(SubcategoryOperation::class,'subcategory_id');
    }



//    public function variables()
//    {
//        return $this->hasManyThrough(Variable::class,SubcategoryVariable::class,'subcategory_id','variable_id');
//    }




}
