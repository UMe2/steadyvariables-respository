<?php

namespace App\Services;

use App\Models\SubCategory;
use App\Models\SubcategoryOperation;

class OperationService
{

    public function mean($subcategory)
    {
        $subcategory = SubCategory::find($subcategory);


        $data = $this->operation_data($subcategory);

        //return $data;
        //return count($data);
        if (count($data) > 0) {
            $mean = array_sum($data) / count($data);
            $closest = $this->closest_value($mean,array_values($data));

            $key = array_search($closest, $data);

            $mean = $subcategory->data_records()->where('batch',$key)->get();
            if ($key !== false) {
                return $mean;
            }

        } else {
           return $mean = 0; // Handle the case where there are no data values to avoid division by zero.
        }




    }
    public function mode( $subcategoryId)
    {
        $subcategory = SubCategory::find($subcategoryId);

        $data = $this->operation_data($subcategory);
        if (count($data) === 0) {
            return null; // Handle the case when there are no data values.
        }

// Count the occurrences of each value.
        $valueCounts = array_count_values($data);

// Find the maximum count.
        $maxCount = max($valueCounts);

// Find all values that have the maximum count (the mode).
        $mode = array_keys($valueCounts, $maxCount);
        return $mode;
    }

    public function median($subcategoryId)
    {
        $subcategory = SubCategory::find($subcategoryId);


        $data = $this->operation_data($subcategory);

        if (count($data) === 0) {
            return null; // Handle the case when there are no data values.
        }

// Sort the data in ascending order.
        sort($data);

        $length = count($data);

// Check if the number of values is even or odd.
        if ($length % 2 === 0) {
            // If it's even, the median is the average of the two middle values.
            $middle1 = $data[($length / 2) - 1];
            $middle2 = $data[$length / 2];
            $median = ($middle1 + $middle2) / 2;
        } else {
            // If it's odd, the median is the middle value.
            $median = $data[($length - 1) / 2];
        }

        return $median;
    }

    public function operation_data($subCategory)
    {
        //return $subCategory;
        $dataColumn = $subCategory->variables()->where('chart_data','=',1)->first();

//        $dataset = $operation->subcategory;

//        $data_variable = $dataset->variables->where('variable_id',$variable->id)->first();

        $data = $dataColumn->data_records->pluck('data','batch')->toArray();


        asort($data);


        return $data;
    }

    public function closest_value($operation,$data)
    {
        $closestValue = $data[0];
        $minDifference = abs($data[0] - $operation);

        foreach ($data as $value) {
            $difference = abs($value - $operation);
            if ($difference < $minDifference) {
                $closestValue = $value;
                $minDifference = $difference;
            }
        }

        return $closestValue;
    }
}
