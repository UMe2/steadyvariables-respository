<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    use HasFactory,HasUuids;

    public function subcategory()
    {
        return $this->hasMany(SubcategoryVariable::class,'variable_id');
    }
}
