<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatasetOperationResource extends JsonResource
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
        "operation"=>$this->operation?->name,
//        "operation_id"=>$this->operation?->id,
        "variable"=>$this->variable?->name,
//        "variable_id"=>$this->variable?->id,
    ];
    }
}
