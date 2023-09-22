<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCategory extends Model
{
    use HasFactory, HasUuids;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class,'data_category_id');
    }

    public function variables()
    {
        return $this->hasManyDeep(SubcategoryVariable::class,[SubCategory::class],
            ['data_category_id'],['subcategory_id']);
    }

}
