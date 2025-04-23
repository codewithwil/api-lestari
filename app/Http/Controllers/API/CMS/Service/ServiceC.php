<?php
namespace App\Http\Controllers\API\CMS\Service;

use App\{
    DTOs\Cms\Service\ServiceDto,
    Http\Controllers\Controller,
    Models\CMS\Service\ServiceContent,
    Repositories\Cms\Service\ServiceRepositoryInterface
};

use Illuminate\{
    Http\Request,
    Support\Facades\Storage
};

class ServiceC extends Controller
{
    public function __construct(
        protected ServiceRepositoryInterface $service
    ) {}

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function show($serviceId)
    {
        return response()->json($this->service->find($serviceId));
    }

    public function store(Request $req)
    {
        // dd($req->all());    
        $validated = $req->validate([
            'description'   => 'nullable|string',
            'header'        => 'nullable|string',
            'services'      => 'array',
            'services.*.title'=> 'required|string|max:75',
            'services.*.linkTitle'=> 'required|string|max:75', 
            'services.*.content'=> 'required|string|max:255',
            'services.*.link'=> 'required|string|url',
            'services.*.linkIcon' => 'nullable|file|mimes:jpeg,png,jpg,svg|max:2048',
            'services.*.image' => 'nullable|file|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    
        $services = [];
    
        foreach ($validated['services'] as $index => $serviceData) {
            $linkIconPath = null;
            if (isset($serviceData['linkIcon']) && $req->hasFile("services.{$index}.linkIcon")) {
                $linkIconPath = $req->file("services.{$index}.linkIcon")->store('button_icons', 'public');
            }
    
            $buttonImagePath = null;
            if (isset($serviceData['image']) && $req->hasFile("services.{$index}.image")) {
                $buttonImagePath = $req->file("services.{$index}.image")->store('service_images', 'public');
            }
    
            $services[] = [
                'title' => $serviceData['title'],
                'linkTitle' => $serviceData['linkTitle'],  
                'content' => $serviceData['content'],
                'link' => $serviceData['link'],
                'linkIcon' => $linkIconPath,
                'image' => $buttonImagePath,
            ];
        }
    
        $serviceDto = new ServiceDto(
            header: $validated['header'],
            desc: $validated['description'],
            services: $services
        );
    
        $service = $this->service->create($serviceDto);
    
        foreach ($services as $serviceData) {
            $service->serviceContent()->create($serviceData);
        }        
    
        return response()->json($service);
    }
    
    
    public function update(Request $req, $serviceId)
    {
        $validated = $req->validate([
            'description'   => 'nullable|string',
            'header'        => 'nullable|string',
            'services'      => 'array',
            'services.*.title' => 'required|string|max:75',
            'services.*.linkTitle' => 'required|string|max:75', // Ensure linkTitle is validated
            'services.*.content' => 'required|string|max:255',
            'services.*.link' => 'required|string|url',
            'services.*.linkIcon' => 'nullable|file|mimes:jpeg,png,jpg,svg|max:2048',
            'services.*.image' => 'nullable|file|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    
        $service = $this->service->find($serviceId);
    
        $services = [];
    
        foreach ($validated['services'] as $index => $serviceData) {
            $linkIconPath = $serviceData['linkIcon'] ?? null;
    
            if (isset($serviceData['linkIcon']) && $req->hasFile("services.{$index}.linkIcon")) {
                if ($linkIconPath && Storage::disk('public')->exists($linkIconPath)) {
                    Storage::disk('public')->delete($linkIconPath);
                }
                $linkIconPath = $req->file("services.{$index}.linkIcon")->store('button_icons', 'public');
            }
    
            $buttonImagePath = $serviceData['image'] ?? null;
    
            if (isset($serviceData['image']) && $req->hasFile("services.{$index}.image")) {
                if ($buttonImagePath && Storage::disk('public')->exists($buttonImagePath)) {
                    Storage::disk('public')->delete($buttonImagePath);
                }
                $buttonImagePath = $req->file("services.{$index}.image")->store('service_images', 'public');
            }
    
            $services[] = [
                'title' => $serviceData['title'],
                'linkTitle' => $serviceData['linkTitle'],  
                'content' => $serviceData['content'],
                'link' => $serviceData['link'],
                'linkIcon' => $linkIconPath,
                'image' => $buttonImagePath,
            ];
        }
    
        $serviceDto = new ServiceDto(
            header: $validated['header'],
            desc: $validated['description'],
            services: $services
        );
    
        $this->service->update($serviceId, $serviceDto);

        $service->serviceContent()->delete();
    
        foreach ($services as $serviceData) {
            $serviceContent = new ServiceContent([
                'service_id' => $service->serviceId,
                'image' => $serviceData['image'],
                'title' => $serviceData['title'],  
                'linkTitle' => $serviceData['linkTitle'],  
                'link' => $serviceData['link'],
                'linkIcon' => $serviceData['linkIcon'],
                'content' => $serviceData['content'],
            ]);
            $serviceContent->save();
        }
    
        return response()->json($service);
    }
    
        
    public function destroy($serviceId)
    {
        $this->service->delete($serviceId);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
