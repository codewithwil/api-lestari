<?php

namespace App\DTOs\Cms\Home;

class HomeDTO
{
    public function __construct(
        public readonly ?string $image,
        public readonly ?string $header,
        public readonly ?string $description,
        public readonly array $buttons 
    ) {}
}
