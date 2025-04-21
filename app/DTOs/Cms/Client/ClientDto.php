<?php

namespace App\DTOs\Cms\Client;

class ClientDto
{
    public function __construct(
        public readonly ?string $image,
        public readonly ?string $name,
    ) {}
}
