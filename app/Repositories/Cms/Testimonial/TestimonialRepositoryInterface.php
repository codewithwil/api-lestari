<?php

namespace App\Repositories\Cms\Testimonial;

use App\DTOs\Cms\Testimonial\TestimonialDto;

interface TestimonialRepositoryInterface
{
    public function getAll();
    public function find(string $testimonialId);
    public function create(TestimonialDto $data);
    public function update(string $testimonialId, TestimonialDto $data);
    public function delete(string $testimonialId);
}
