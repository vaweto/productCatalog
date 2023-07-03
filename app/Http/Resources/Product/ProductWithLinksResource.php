<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductWithLinksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'info' => new ProductResource($this),
            'action_links' => [
                'update' => [
                    'method' => 'post',
                    'url' => route('product.update', ['product' => $this->id]),
                ],
                'destroy' => [
                    'method' => 'delete',
                    'url' => route('product.destroy', ['product' => $this->id]),
                ],
            ],
        ];
    }
}
