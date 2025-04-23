<?php

namespace App\DTOs\Cms\Service;

class ServiceDto
{
    public function __construct(
        public readonly ?string $header,
        public readonly ?string $desc,
        public readonly array $services
    ) {}
}
