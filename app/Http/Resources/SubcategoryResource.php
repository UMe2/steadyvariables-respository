<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if (isset($request->subcategory)){
            return [
                "id"=>$this->id,
                "name"=>$this->name,
                "variables"=> SubcategoryVariableResoource::collection($this->variables) ,
                "category"=>$this->category?->name,
                "dataRecord"=> DataRecordResource::collection($this->data_records)->groupBy("batch")
            ];
        }
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "variables"=>$this->variables?->count(),
            "category"=>$this->category?->name
        ];
    }
}
