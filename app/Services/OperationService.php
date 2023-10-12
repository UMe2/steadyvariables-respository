<?php

namespace App\Services;

use App\Models\SubcategoryOperation;

class OperationService
{

    public function mean( $operation)
    {
        $operation = SubcategoryOperation::find($operation);

        $data = $this->operation_data($operation);

        $sum = array_sum($data);
        $mean = $sum/count($data);

        return $mean;

    }
    public function mode( $operation)
    {
        $operation = SubcategoryOperation::find($operation);

        $data = $this->operation_data($operation);
        sort($data);
        $count = count($data);

//        $sum = array_sum($data);
//        $mean = $sum/count($data);

        return $count;

    }

    public function operation_data(SubcategoryOperation $operation)
    {
        $variable = $operation->variable;

        $dataset = $operation->subcategory;

        $data_variable = $dataset->variables->where('variable_id',$variable->id)->first();

        $data = $data_variable->data_records->pluck('data')->toArray();

        return $data;
    }
}
