<?php

namespace App\Http\Controllers\API\CMS\Home;

use App\{
    DTOs\Cms\Home\HomeDTO,
    Http\Controllers\Controller,
    Repositories\Cms\Home\HomeRepositoryInterface,
};

use Illuminate\Http\Request;
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
        return response()->json($this->home->find($homeId));
    }

    public function store(Request $req)
    {
        $validated = $req->validate([
            'description'          => 'nullable|string',
            'header'               => 'nullable|string',
            'image'                => 'nullable|string', // Image can be a base64 string
            'buttons'              => 'array',
            'buttons.*.text'       => 'required|string',
            'buttons.*.link'       => 'required|string|url',
            'buttons.*.icon'       => 'nullable|string', // Icon could be base64 SVG or raw SVG
            'buttons.*.background' => 'required|string',
            'buttons.*.color'      => 'required|string',
        ]);
    
        $imagePath = null;
        if ($req->has('image')) {
            $base64Image = $req->input('image'); 
    
            if (preg_match('/^data:image\/(jpg|jpeg|png);base64,/', $base64Image, $matches)) {
                $imageType = $matches[1]; 
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image)); 
    
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
                    $base64Icon = $button['icon'];
                    $iconData = base64_decode(preg_replace('/^data:image\/svg\+xml;base64,/', '', $base64Icon));
    
                    $iconPath = 'home_icons/' . uniqid() . '.svg';
                    Storage::disk('public')->put($iconPath, $iconData);
    
                    $button['icon'] = $iconPath;
                } 
                else {
                    return response()->json(['error' => 'Invalid image format'], 400); 
                }
            }
        }
    
        $dto = new HomeDTO(
            image: $imagePath,
            header: $validated['header'] ?? null,
            description: $validated['description'] ?? null,
            buttons: $buttons
        );
    
        return response()->json($this->home->create($dto));
    }
    
    

    public function update(Request $req, $homeId)
    {
        $validated = $req->validate([
            'description'          => 'nullable|string',
            'header'               => 'nullable|string',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'buttons'              => 'array',
            'buttons.*.text'       => 'required|string',
            'buttons.*.link'       => 'required|string|url',
            'buttons.*.icon'      => 'required|string',
            'buttons.*.background' => 'required|string',
            'buttons.*.color'      => 'required|string',
        ]);

        $home = $this->home->find($homeId);

        $imagePath = $home->image; 
        if ($req->hasFile('image')) {
            $imagePath = $req->file('image')->store('home_images', 'public');
        }

        $dto = new HomeDTO(
            image: $imagePath,
            header: $validated['header'] ?? null,
            description: $validated['description'] ?? null,
            buttons: $validated['buttons'] ?? []
        );

        return response()->json($this->home->update($homeId, $dto));
    }

    public function destroy($homeId)
    {
        $this->home->delete($homeId);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
