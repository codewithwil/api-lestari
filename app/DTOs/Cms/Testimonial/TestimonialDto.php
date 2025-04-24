<?php

namespace App\DTOs\Cms\Testimonial;

class TestimonialDto
{
    public function __construct(
        public readonly ?string $header,
        public readonly ?string $desc,
        public readonly array $testimonials
    ) {}
}
