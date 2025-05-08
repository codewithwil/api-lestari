<?php

namespace App\DTOs\Cms\CountUp;

class CountUpDto
{
    public function __construct(
        public readonly ?string $icon,
        public readonly ?string $title,
        public readonly ?int $amount,
    ) {}
}
