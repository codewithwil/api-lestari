<?php
namespace App\Http\Controllers\API\CMS\Service;

use App\{
    DTOs\Cms\Service\ServiceDto,
    Http\Controllers\Controller,
    Models\CMS\Service\ServiceContent,
    Repositories\Cms\Service\ServiceRepositoryInterface
};
use App\Traits\Base64FileHandler;
use App\Traits\dbTransac;
use Illuminate\{
    Http\Request,
    Support\Facades\Storage
};

class ServiceC extends Controller
{
    use dbTransac, Base64FileHandler;
    public function __construct(
        protected ServiceRepositoryInterface $service
    ) {}

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function show($serviceId)
    {
        $service = $this->service->find($serviceId);
    
        $service->serviceContent->each(function ($item) {
            $item->image = $item->image 
                ? $this->convertToBase64(storage_path("app/public/{$item->image}")) 
                : null;
    
            $item->linkIcon = $item->linkIcon 
                ? $this->convertToBase64(storage_path("app/public/{$item->linkIcon}")) 
                : null;
        });
    
        return response()->json($service);
    }    

    public function store(Request $req)
    {  
        return $this->dbTransaction(function () use ($req) {
            $validated = $req->validate([
                'description'               => 'nullable|string',
                'header'                    => 'nullable|string',
                'services'                  => 'array',
                'services.*.title'          => 'required|string|max:75',
                'services.*.image'          => 'nullable|string',
                'services.*.content'        => 'required|string|max:255',
                'services.*.linkIcon'       => 'nullable|string',
                'services.*.linkTitle'      => 'required|string|max:75', 
                'services.*.link'           => 'required|string|url',
                'services.*.linkBackground' => 'required|string|max:20',
                'services.*.linkColor'      => 'required|string|max:20',
            ]);
        
            $services = [];
        
            foreach ($validated['services'] as $serviceData) {
                $imagePath   = $this->storeBase64Image($serviceData['image'] ?? '', 'service_images') ?: null;
                $linkIconPath = $this->storeBase64Image($serviceData['linkIcon'] ?? '', 'service_icons') ?: null;
        
                $services[] = [
                    'title'          => $serviceData['title'],
                    'image'          => $imagePath,
                    'content'        => $serviceData['content'],
                    'linkIcon'       => $linkIconPath,
                    'linkTitle'      => $serviceData['linkTitle'],  
                    'link'           => $serviceData['link'],
                    'linkBackground' => $serviceData['linkBackground'],
                    'linkColor'      => $serviceData['linkColor'],
                ];
            }
        
            $serviceDto = new ServiceDto(
                header: $validated['header'],
                desc: $validated['description'],
                services: $services
            );
        
            $service = $this->service->create($serviceDto);
    
            return response()->json($service);
        });
    }
    
    
    public function update(Request $req, $serviceId)
    {
        return $this->dbTransaction(function () use ($req, $serviceId) {
            $validated = $req->validate([
                'description'               => 'nullable|string',
                'header'                    => 'nullable|string',
                'services'                  => 'array',
                'services.*.title'          => 'nullable|string|max:75',
                'services.*.image'          => 'nullable|string',
                'services.*.content'        => 'nullable|string|max:255',
                'services.*.linkTitle'      => 'nullable|string|max:75', 
                'services.*.linkIcon'       => 'nullable|string',
                'services.*.link'           => 'nullable|string|url',
                'services.*.linkBackground' => 'nullable|string|max:20',
                'services.*.linkColor'      => 'nullable|string|max:20',
            ]);
        
            $service = $this->service->find($serviceId);
        
            $services = [];
        
            foreach ($validated['services'] as $index => $serviceData) {
                $old = $service->serviceContent[$index] ?? null;
    
                $services[] = [
                    'title'          => $serviceData['title'],
                    'image'          => $this->handleUpdatedImage($serviceData['image'] ?? '', $old?->image, 'service_images'),
                    'content'        => $serviceData['content'],
                    'linkTitle'      => $serviceData['linkTitle'],  
                    'linkIcon'       => $this->handleUpdatedImage($serviceData['linkIcon'] ?? '', $old?->linkIcon, 'service_icons'),
                    'link'           => $serviceData['link'],
                    'linkBackground' => $serviceData['linkBackground'],
                    'linkColor'      => $serviceData['linkColor'],
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
                    'service_id'     => $service->serviceId,
                    'image'          => $serviceData['image'],
                    'title'          => $serviceData['title'],  
                    'content'        => $serviceData['content'],
                    'linkTitle'      => $serviceData['linkTitle'],  
                    'linkIcon'       => $serviceData['linkIcon'],
                    'link'           => $serviceData['link'],
                    'linkBackground' => $serviceData['linkBackground'],
                    'linkColor'      => $serviceData['linkColor'],
                ]);
                $serviceContent->save();
            }
            
        
            return response()->json($service);
        });
    }
    
        
    public function destroy($serviceId)
    {
        $this->service->delete($serviceId);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
