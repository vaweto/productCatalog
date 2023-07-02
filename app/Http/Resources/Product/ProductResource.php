<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'price' => $this->price,
            'release_date' => $this->release_date,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'category_id' => $this->category->id,
                    'category_name' => $this->category->name,
                ];
            }),
        ];
    }
}
