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

        if ($request->routeIs('guest.subcategory')){
            return [
                "id"=>$this->id,
                "name"=>$this->name,
                "variables"=> SubcategoryVariableResoource::collection($this->variables->sortByDesc('first_column')),
                "category"=>$this->category?->name,
                'description'=>$this->description,
                "dataRecord"=> DataRecordResource::collection($this->data_records)
                    ->groupBy("batch"),
                "operations"=>DatasetOperationResource::collection($this->operations),

            ];
        }

        if ($request->routeIs('guest.index')){
            return [
                "id"=>$this->id,
                "name" =>$this->name,
            ];
        }

        if ($request->routeIs('guest.search')){
            return [
                "id"=>$this->id,
                "name" =>$this->name,
                "category"=>$this->category?->name,
                'description'=>$this->description,
                "category_id"=>$this->category?->id
            ];
        }


        if (isset($request->subcategory)){
            return [
                "id"=>$this->id,
                "name"=>$this->name,
                "variables"=> SubcategoryVariableResoource::collection($this->variables) ,
                "category"=>$this->category?->name,
                'description'=>$this->description,
                "dataRecord"=> DataRecordResource::collection($this->data_records)->groupBy("batch"),
                "operations"=>DatasetOperationResource::collection($this->operations)
            ];
        }
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "variables"=>$this->variables?->count(),
            "description"=> $this->description,
            "category"=>$this->category?->name
        ];
    }
}
