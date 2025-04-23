<?php

namespace App\Http\Controllers\API\CMS\Home;

use App\{
    DTOs\Cms\Home\HomeDTO,
    Http\Controllers\Controller,
    Repositories\Cms\Home\HomeRepositoryInterface,
};

use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description'   => 'nullable|string',
            'header'        => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'buttons'       => 'array',
            'buttons.*.text'=> 'required|string',
            'buttons.*.link'=> 'required|string|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('home_images', 'public');
        }

        $dto = new HomeDTO(
            image: $imagePath,
            header: $validated['header'] ?? null,
            description: $validated['description'] ?? null,
            buttons: $validated['buttons'] ?? []
        );

        return response()->json($this->home->create($dto));
    }

    public function update(Request $request, $homeId)
    {
        $validated = $request->validate([
            'description'   => 'nullable|string',
            'header'        => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'buttons'       => 'array',
            'buttons.*.text'=> 'required|string',
            'buttons.*.link'=> 'required|string|url',
        ]);

        $home = $this->home->find($homeId);

        $imagePath = $home->image; 
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('home_images', 'public');
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
