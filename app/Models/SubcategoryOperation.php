<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcategoryOperation extends Model
{
    use HasFactory, HasUuids;

    protected $guarded=[];

    public function operation()
    {
        return $this->belongsTo(Operation::class,'operation_id');
    }


    public function variable()
    {
        return $this->belongsTo(Variable::class,'variable_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'subcategory_id');
    }
}
