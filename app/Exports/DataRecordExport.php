<?php

namespace App\Exports;

use App\Models\SubCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataRecordExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $subcategory;
    private $subcategoryVariables;
    private $heading;
    public function __construct($subcategory,$variables,$heading)
    {
        $this->subcategory = $subcategory;
        $this->subcategoryVariables = $variables;
        $this->heading = $heading;
    }

    public function collection()
    {
        return $this->subcategoryVariables ;
    }

    public function headings(): array
    {
        // Use the keys of the first item in the data array as headings
        return $this->heading ;
    }
}
