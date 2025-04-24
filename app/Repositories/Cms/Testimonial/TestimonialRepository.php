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
        $service = Testimonial::create([
            'header' => $data->header,
            'desc'  => $data->desc,
        ]);

        foreach ($data->testimonials as $button) {
            $service->TestimonialContent()->create($button);
        }

        return $service->load('TestimonialContent');
    }

    public function update(string $testimonialId, TestimonialDto $data)
    {
        $service = Testimonial::findOrFail($testimonialId);
        $service->update([
            'header' => $data->header,
            'desc'  => $data->desc,
        ]);

        $service->TestimonialContent()->delete();
        foreach ($data->testimonials as $button) {
            $service->TestimonialContent()->create($button);
        }

        return $service->load('TestimonialContent');
    }

    public function delete(string $testimonialId)
    {
        $service = Testimonial::findOrFail($testimonialId);
        $service->delete();
    }
}
