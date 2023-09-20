<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if (isset($request->category)){
            return [
                'id'=>$this->id,
                'name'=>$this->name,
                'subcategories'=>SubcategoryResource::collection($this->subcategories),
                "variables"=> $this->variables,
            ];
        }
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'numberOfSubcategories'=>$this->subcategories?->count()
        ];
    }
}
