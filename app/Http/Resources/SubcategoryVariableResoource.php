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
        return [
            "id"=>$this->id,
            "variable_id"=>$this->variable?->id,
            "subcategory"=>$this->subcategory?->name,
            "variable"=>$this->variable?->name,
            "required"=>$this->required==0? "no":"yes",
            "isKey"=>$this->isKey==0? "no":"yes",
        ];
    }
}
