<?php

namespace App\Http\Resources;

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
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "price" => $this->price,
            "stock" => $this->stock,
            "sku" => $this->sku,
            "category" => [
                "name" => $this->category->name,
                "slug" => $this->category->slug,
            ],
            "colors" => $this->colors->map(function ($color): array {
                return [
                    "name" => $color->name,
                    "hexCode" => $color->hex_code,
                ];
            }),
            "sizes" => $this->sizes->map(function ($size): array {
                return [
                    "name" => $size->name,
                ];
            }),

            "createdAt" => $this->created_at->format('M d Y'),

        ];

    }
}
