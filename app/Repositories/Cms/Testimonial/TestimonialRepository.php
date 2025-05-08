<?php

namespace App\Repositories\Cms\Testimonial;

use App\DTOs\Cms\Testimonial\TestimonialDto;
use App\Models\CMS\Testimonial\Testimonial;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    public function getAll()
    {
        return Testimonial::with('TestimonialContent')->get();
    }

    public function find(string $testimonialId)
    {
        return Testimonial::with('TestimonialContent')->findOrFail($testimonialId);
    }

    public function create(TestimonialDto $data)
    {
        $testimonial = Testimonial::create([
            'header' => $data->header,
            'desc'   => $data->desc,
        ]);

        foreach ($data->testimonials as $button) {
            $testimonial->TestimonialContent()->create($button);
        }

        return $testimonial->load('TestimonialContent');
    }

    public function update(string $testimonialId, TestimonialDto $data)
    {
        $testimonial = Testimonial::findOrFail($testimonialId);
        $testimonial->update([
            'header' => $data->header,
            'desc'  => $data->desc,
        ]);

        $testimonial->TestimonialContent()->delete();
        foreach ($data->testimonials as $button) {
            $testimonial->TestimonialContent()->create($button);
        }

        return $testimonial->load('TestimonialContent');
    }

    public function delete(string $testimonialId)
    {
        $testimonial = Testimonial::findOrFail($testimonialId);
        $testimonial->delete();
    }
}
