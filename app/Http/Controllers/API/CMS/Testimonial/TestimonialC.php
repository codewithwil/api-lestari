<?php

namespace App\Http\Controllers\API\CMS\Testimonial;

use App\{
    DTOs\Cms\Testimonial\TestimonialDto,
    Http\Controllers\Controller,
    Repositories\Cms\Testimonial\TestimonialRepositoryInterface,
    Traits\dbTransac,
    Traits\Base64FileHandler
};

use Illuminate\Http\Request;

class TestimonialC extends Controller
{
    use dbTransac, Base64FileHandler;
    public function __construct(
        protected TestimonialRepositoryInterface $testimonial
    ) {}

    public function index()
    {
        return response()->json($this->testimonial->getAll());
    }

    public function show($testimonialId)
    {
        return response()->json($this->testimonial->find($testimonialId));
    }

    public function store(Request $req)
    {
        return $this->dbTransaction(function () use ($req) {
            $validated = $req->validate([
                'desc'                 => 'nullable|string',
                'header'               => 'nullable|string',
                'testimonials'         => 'array',
                'testimonials.*.name'  => 'required|string|max:75',
                'testimonials.*.desc'  => 'required|string|max:255',
                'testimonials.*.image' => 'nullable|string', 
            ]);
    
            $testimonialItems = [];
    
            foreach ($validated['testimonials'] ?? [] as $testimonialData) {
                $imagePath = $this->storeBase64Image($testimonialData['image'] ?? '', 'testimonial_images') ?: null;
    
                $testimonialItems[] = [
                    'image' => $imagePath,
                    'name'  => $testimonialData['name'],
                    'desc'  => $testimonialData['desc'],
                ];
            }
    
            $dto = new TestimonialDto(
                header: $validated['header'],
                desc: $validated['desc'],
                testimonials: $testimonialItems
            );
    
            $testimonial = $this->testimonial->create($dto);
    
            return response()->json($testimonial);
        });
    }
    
    public function update(Request $req, $testimonialId)
    {
        return $this->dbTransaction(function () use ($req, $testimonialId) {
            $validated = $req->validate([
                'desc'                 => 'nullable|string',
                'header'               => 'nullable|string',
                'testimonials'         => 'array',
                'testimonials.*.name'  => 'required|string|max:75',
                'testimonials.*.desc'  => 'required|string|max:255',
                'testimonials.*.image' => 'nullable|file|mimes:jpeg,png,jpg,svg|max:2048',
            ]);
    
            $testimonial  = $this->testimonial->find($testimonialId);
            $testimonials = $validated['testimonials'] ?? [];
    
            foreach ($testimonials as $index => $testimonialData) {
                $old          = $testimonials->TestimonialContent[$index] ?? null;
    
                $testimonials[] = [
                    'name'  => $testimonialData['name'],
                    'desc'  => $testimonialData['desc'],
                    'image' => $this->handleUpdatedImage($testimonialData['image'] ?? '', $old?->image, 'testimonial_images'),
                ];
            }
    
            $testimonialDto = new TestimonialDto(
                header: $validated['header'],
                desc: $validated['description'],
                testimonials: $testimonials
            );
    
            return response()->json($testimonial->load('testimonialContent'));
        });
    } 
        
    public function destroy($testimonialId)
    {
        return $this->dbTransaction(function () use ($testimonialId) {
            $this->testimonial->delete($testimonialId);
            return response()->json(['message' => 'Deleted successfully']);
        });
    }

}
