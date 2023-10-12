<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcategoryVariable extends Model
{
    use HasFactory, HasUuids;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $guarded=[];
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'subcategory_id');
    }

    public function variable()
    {
        return $this->belongsTo(Variable::class,'variable_id');
    }

    public function data_records()
    {
        return $this->hasMany(DataRecord::class,'subcategory_variable_id');
    }

}
