<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory, HasUuids;

    public function dataset()
    {
        return $this->hasMany(SubcategoryOperation::class,'operation_id');
    }
}
