<?php

namespace App\DTOs;

readonly class AdminProductDto
{
    public function __construct(
        public string $name,
        public string $slug,
        public float $price,
        public int $stock,
        public int $category_id,
        public ?string $desctiption,

    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['slug'],
            $data['price'],
            $data['stock'],
            $data['category_id'],
            $data['description'] ?? null,

        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'description' => $this->desctiption,
        ];
    }
}
