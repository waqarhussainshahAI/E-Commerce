<?php

namespace App\DTOs;

readonly class ProceedOrderDto
{
    public function __construct(

        public string $fullName,
        public string $email,
        public string $streetAddress,
        public string $city,
        public string $province,
        public string $zipCode,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            fullName: $data['full_name'],
            email: $data['email'],
            streetAddress: $data['street_address'],
            city: $data['city'],
            province: $data['province'],
            zipCode: $data['zip_code'],
        );
    }

    public function toArray(): array
    {
        return [

            'full_name' => $this->fullName,
            'email' => $this->email,
            'street_address' => $this->streetAddress,
            'city' => $this->city,
            'province' => $this->province,
            'zip_code' => $this->zipCode,
        ];
    }
}
