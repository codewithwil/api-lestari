<?php

namespace App\DTOs\Cms\About;

class AboutDTO
{
    public function __construct(
        public readonly ?string $image,
        public readonly ?string $header,
        public readonly ?string $desc,
        public readonly array $abouts 
    ) {}
}
