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

//            $key = array_search($closest, $data);
//
//            $mean = $subcategory->data_records()->where('batch',$key)->get();
          return  $mean;

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
        return doubleval($mode[0]) ;
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

        return doubleval($median);
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

    public function rate_of_change($subcategoryId)
    {
        $subcategory = SubCategory::find($subcategoryId);


        $data = $this->operation_data($subcategory);

        $array_keys = array_keys($data);
        $data = array_values( $data);

        $label =$this->chart_label($subcategory,$array_keys);
       // return $label;


        //return $array_keys;

        if (count($data) < 2) {
            return null; // Handle the case when there are not enough data points to calculate a rate of change.
        }

        $roc = [];


        for ($i = 1; $i < count($data); $i++) {
            $oldValue = $data[$i - 1];
            $newValue = $data[$i];

            if ($oldValue != 0) {
                $rateOfChange = ($newValue - $oldValue) / $oldValue;
                $roc[$label[$i]] = $rateOfChange;
            } else {
                // Handle the case when the old value is zero (to avoid division by zero).
                $roc[] = null;
            }
        }



        return $roc;
    }

    public function chart_label(SubCategory $subCategory,$batches)
    {
           $variable = $subCategory->variables->where('chart_label',true)->first();
           $data=[];
           foreach($batches as $batch ){
               $data[] = $variable->data_records->where('batch',$batch)->first()->data;
           }

            asort($data);
           return array_values($data);


    }
}
