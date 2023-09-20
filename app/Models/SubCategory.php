<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory,HasUuids;

    public function category()
    {
        return $this->belongsTo(DataCategory::class,'data_category_id');
    }

    public function datas()
    {

    }
}
