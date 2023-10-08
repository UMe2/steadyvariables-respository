<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryVariableResoource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if ($request->routeIs("template") || $request->routeIs("admin.subcategory.template")){
            return [
                "subcategory"=>"Subcategory Name",
                "variable"=>$this->variable?->name,
                'firstColumn'=>$this->first_column == 1 ? true : false,
            ];
        }
        return [
            "id"=>$this->id,
            "variable_id"=>$this->variable?->id,
            "subcategory"=>$this->subcategory?->name,
            "variable"=>$this->variable?->name,
            'firstColumn'=>$this->first_column == 1 ? true : false,
        ];
    }
}
