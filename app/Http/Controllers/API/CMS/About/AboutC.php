<?php

namespace App\Http\Controllers\API\CMS\About;

use App\{
    DTOs\Cms\About\AboutDTO,
    Http\Controllers\Controller,
    Repositories\Cms\About\AboutRepositoryInterface,
};

use Illuminate\Http\Request;

class AboutC extends Controller
{
    public function __construct(
        protected AboutRepositoryInterface $about
    ) {}

    public function index()
    {
        return response()->json($this->about->getAll());
    }

    public function show($homeId)
    {
        return response()->json($this->about->find($homeId));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desc'   => 'nullable|string',
            'header'        => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('about_images', 'public');
        }

        $dto = new AboutDTO(
            image:  $imagePath,
            header: $validated['header'] ?? null,
            desc:   $validated['desc'] ?? null,
        );

        return response()->json($this->about->create($dto));
    }

    public function update(Request $request, $homeId)
    {
        $validated = $request->validate([
            'desc'          => 'nullable|string',
            'header'        => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'buttons'       => 'array',
            'buttons.*.text'=> 'required|string',
            'buttons.*.link'=> 'required|string|url',
        ]);

        $about = $this->about->find($homeId);

        $imagePath = $about->image; 
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('about_images', 'public');
        }

        $dto = new AboutDTO(
            image:  $imagePath,
            header: $validated['header'] ?? null,
            desc:   $validated['desc'] ?? null,
        );

        return response()->json($this->about->update($homeId, $dto));
    }

    public function destroy($homeId)
    {
        $this->about->delete($homeId);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
