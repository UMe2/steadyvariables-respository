<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataRecordResource extends JsonResource
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
                "variable"=>$this->subcategory_variable?->variable?->name,
                "data"=> number_format($this->data,2),
            ];
        }

        return [
            "id"=>$this->id,
            "variable"=>$this->subcategory_variable?->variable?->name,
            "data"=> number_format($this->data,2),
            "subcategory"=> $this->subcategory?->name,
            "category"=>$this->subcategory?->category?->name,
        ];
    }
}
