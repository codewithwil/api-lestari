<?php

namespace App\Http\Controllers\API\CMS\Home;

use App\{
    DTOs\Cms\Home\HomeDTO,
    Http\Controllers\Controller,
    Repositories\Cms\Home\HomeRepositoryInterface,
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeC extends Controller
{
    public function __construct(
        protected HomeRepositoryInterface $home
    ) {}

    public function index()
    {
        return response()->json($this->home->getAll());
    }

    public function show($homeId)
    {
        $home = $this->home->find($homeId);
        if ($home->image) {
            $imagePath = storage_path('app/public/' . $home->image);  
            if (file_exists($imagePath)) {
                $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
                $imageData = base64_encode(file_get_contents($imagePath));
                $mimeType = 'image/' . $imageExtension; 
                $home->image = 'data:' . $mimeType . ';base64,' . $imageData;  
            }
        }

        foreach ($home->buttons as $button) {
            if ($button->icon) {
                $iconPath = storage_path('app/public/' . $button->icon);  
                if (file_exists($iconPath)) {
                    $iconExtension = pathinfo($iconPath, PATHINFO_EXTENSION);
                    $iconData = base64_encode(file_get_contents($iconPath));
                    
                    if ($iconExtension == 'svg') {
                        $mimeType = 'image/svg+xml';  
                    } else {
                        $mimeType = 'image/' . $iconExtension;  
                    }

                    $button->icon = 'data:' . $mimeType . ';base64,' . $iconData;  
                }
            }
        }
        
        return response()->json($home);
    }


    public function store(Request $req)
    {
        DB::beginTransaction();
    
        try {
            $validated = $req->validate([
                'description'          => 'nullable|string',
                'header'               => 'nullable|string',
                'image'                => 'nullable|string', 
                'buttons'              => 'array',
                'buttons.*.text'       => 'required|string',
                'buttons.*.link'       => 'required|string|url',
                'buttons.*.icon'       => 'nullable|string', 
                'buttons.*.background' => 'required|string',
                'buttons.*.color'      => 'required|string',
            ]);
    
            $imagePath = null;
            if ($req->has('image')) {
                $base64Image = $req->input('image');
            
                if (preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $base64Image, $matches)) {
                    $imageType = $matches[1];
                    $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
                    $image = base64_decode($base64Data);
            
                    if ($image === false) {
                        return response()->json(['error' => 'Failed to decode image'], 400);
                    }
            
                    $imagePath = 'home_images/' . uniqid() . '.' . $imageType;
                    Storage::disk('public')->put($imagePath, $image);
                } else {
                    return response()->json(['error' => 'Invalid image format'], 400);
                }
            }
            
    
            $buttons = $validated['buttons'] ?? [];
            foreach ($buttons as &$button) {
                if (isset($button['icon']) && !empty($button['icon'])) {
                    if (preg_match('/^data:image\/svg\+xml;base64,/', $button['icon'])) {
                        $base64Data = substr($button['icon'], strpos($button['icon'], ',') + 1);
                        $iconData = base64_decode($base64Data);
                
                        if ($iconData === false) {
                            return response()->json(['error' => 'Failed to decode SVG icon'], 400);
                        }
                
                        $iconPath = 'home_icons/' . uniqid() . '.svg';
                        Storage::disk('public')->put($iconPath, $iconData);
                
                        $button['icon'] = $iconPath;
                    } else {
                        return response()->json(['error' => 'Invalid icon format'], 400);
                    }
                }                
            }
    
            $dto = new HomeDTO(
                image: $imagePath,
                header: $validated['header'] ?? null,
                description: $validated['description'] ?? null,
                buttons: $buttons
            );
    
            $result = $this->home->create($dto);
            
            DB::commit();
            return response()->json($result);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $req, $homeId)
    {
        $validated = $req->validate([
            'description'          => 'nullable|string',
            'header'               => 'nullable|string',
            'image'                => 'nullable|string',
            'buttons'              => 'array',
            'buttons.*.text'       => 'required|string',
            'buttons.*.link'       => 'required|string|url',
            'buttons.*.icon'       => 'nullable|string',
            'buttons.*.background' => 'required|string',
            'buttons.*.color'      => 'required|string',
        ]);
    
        $home = $this->home->find($homeId);
    
        // Handling image upload
        $imagePath = $home->image; 
        if ($req->has('image')) {
            $base64Image = $req->input('image');
            
            if (preg_match('/^data:image\/(jpeg|jpg|png|gif);base64,/', $base64Image, $matches)) {
                $imageType = $matches[1];
                $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
                $image = base64_decode($base64Data);
    
                if ($image === false) {
                    return response()->json(['error' => 'Failed to decode image'], 400);
                }
    
                $imagePath = 'home_images/' . uniqid() . '.' . $imageType;
                Storage::disk('public')->put($imagePath, $image);
            } else {
                return response()->json(['error' => 'Invalid image format'], 400);
            }
        }
    
        // Handling button icons
        $buttons = $validated['buttons'] ?? [];
        foreach ($buttons as &$button) {
            if (isset($button['icon']) && !empty($button['icon'])) {
                // Decode and save icon if it's in base64 format
                if (preg_match('/^data:image\/(svg\+xml|jpeg|jpg|png);base64,/', $button['icon'], $matches)) {
                    $iconType = $matches[1];
                    $base64Data = substr($button['icon'], strpos($button['icon'], ',') + 1);
                    $iconData = base64_decode($base64Data);
    
                    if ($iconData === false) {
                        return response()->json(['error' => 'Failed to decode SVG icon'], 400);
                    }

                    $iconPath = 'home_icons/' . uniqid() . '.' . $iconType;
                    Storage::disk('public')->put($iconPath, $iconData);
    
                    $button['icon'] = $iconPath; 
                } else {
                    return response()->json(['error' => 'Invalid icon format'], 400);
                }
            }
        }
    
        $dto = new HomeDTO(
            image: $imagePath,
            header: $validated['header'] ?? null,
            description: $validated['description'] ?? null,
            buttons: $buttons
        );
        return response()->json($this->home->update($homeId, $dto));
    }
    

    public function delete($homeId)
    {
        $this->home->delete($homeId);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
