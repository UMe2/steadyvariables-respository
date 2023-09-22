<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcategoryVariableRecord extends Model
{
    use HasFactory,HasUuids;

    public function subcategoryVariable()
    {
        return $this->belongsTo(SubcategoryVariable::class,'subcategory_variable_id');
    }
}
