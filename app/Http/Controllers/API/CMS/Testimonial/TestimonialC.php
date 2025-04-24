<?php

namespace App\Http\Controllers\API\CMS\Testimonial;

use App\{
    DTOs\Cms\Testimonial\TestimonialDto,
    Http\Controllers\Controller,
    Repositories\Cms\Testimonial\TestimonialRepositoryInterface,
    Traits\dbTransac
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialC extends Controller
{
    use dbTransac;
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
        $validated = $req->validate([
            'description'          => 'nullable|string',
            'header'               => 'nullable|string',
            'testimonials'         => 'array',
            'testimonials.*.name'  => 'required|string|max:75',
            'testimonials.*.desc'  => 'required|string|max:255',
            'testimonials.*.image' => 'nullable|file|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    
        return $this->dbTransaction(function () use ($validated, $req) {
            $testimonials = [];
    
            foreach ($validated['testimonials'] as $index => $testimonialData) {
                $imagePath = null;
                if (isset($testimonialData['image']) && $req->hasFile("testimonials.{$index}.image")) {
                    $imagePath = $req->file("testimonials.{$index}.image")->store('testimonial_images', 'public');
                }
    
                $testimonials[] = [
                    'image' => $imagePath,
                    'name'  => $testimonialData['name'],
                    'desc'  => $testimonialData['desc'],
                ];
            }
    
            $existing = \App\Models\CMS\Testimonial\Testimonial::where('header', $validated['header'])->first();
    
            if ($existing) {
                foreach ($testimonials as $testimonialData) {
                    $existing->testimonialContent()->create($testimonialData);
                }
    
                return response()->json($existing->load('testimonialContent'));
            }
    
            $dto = new \App\DTOs\Cms\Testimonial\TestimonialDto(
                header: $validated['header'],
                desc: $validated['description'],
                testimonials: $testimonials
            );
    
            $created = $this->testimonial->create($dto);
    
            return response()->json($created);
        });
    } 
    
    public function update(Request $req, $testimonialId)
    {
        $validated = $req->validate([
            'description'          => 'nullable|string',
            'header'               => 'nullable|string',
            'testimonials'         => 'array',
            'testimonials.*.name'  => 'required|string|max:75',
            'testimonials.*.desc'  => 'required|string|max:255',
            'testimonials.*.image' => 'nullable|file|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    
        return $this->dbTransaction(function () use ($validated, $req, $testimonialId) {
            $testimonial  = $this->testimonial->find($testimonialId);
            $testimonials = [];
    
            foreach ($validated['testimonials'] as $index => $testimonialData) {
                $linkIconPath = $testimonialData['linkIcon'] ?? null;
    
                if (isset($testimonialData['linkIcon']) && $req->hasFile("testimonials.{$index}.linkIcon")) {
                    if ($linkIconPath && Storage::disk('public')->exists($linkIconPath)) {
                        Storage::disk('public')->delete($linkIconPath);
                    }
                    $linkIconPath = $req->file("testimonials.{$index}.linkIcon")->store('button_icons', 'public');
                }
    
                $imagePath = $testimonialData['image'] ?? null;
    
                if (isset($testimonialData['image']) && $req->hasFile("testimonials.{$index}.image")) {
                    if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                    $imagePath = $req->file("testimonials.{$index}.image")->store('testimonial_images', 'public');
                }
    
                $testimonials[] = [
                    'name'  => $testimonialData['name'],
                    'desc'  => $testimonialData['desc'],
                    'image' => $imagePath,
                ];
            }
    
            $testimonialDto = new TestimonialDto(
                header: $validated['header'],
                desc: $validated['description'],
                testimonials: $testimonials
            );
    
            $this->testimonial->update($testimonialId, $testimonialDto);
            $testimonial->testimonialContent()->delete();
    
            foreach ($testimonials as $testimonialData) {
                $testimonial->testimonialContent()->create($testimonialData);
            }
    
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
