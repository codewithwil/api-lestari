<?php

namespace App\Http\Controllers\API\CMS\Home;

use App\{
    Traits\Base64FileHandler,
    Traits\dbTransac,
    DTOs\Cms\Home\HomeDTO,
    Http\Controllers\Controller,
    Repositories\Cms\Home\HomeRepositoryInterface,
};

use Illuminate\Http\Request;

class HomeC extends Controller
{
    use Base64FileHandler, dbTransac;
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
            $home->image = $this->convertToBase64(storage_path("app/public/{$home->image}"));
        }

        foreach ($home->buttons as $button) {
            if ($button->icon) {
                $button->icon = $this->convertToBase64(storage_path("app/public/{$button->icon}"));
            }
        }

        return response()->json($home);
    }

    public function store(Request $req)
    {
        return $this->dbTransaction(function () use ($req) {
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

            $imagePath = $this->storeBase64Image($validated['image'] ?? '', 'home_images') ?: null;

            $buttons = $validated['buttons'] ?? [];
            foreach ($buttons as &$button) {
                $button['icon'] = $this->storeBase64Image($button['icon'] ?? '', 'home_icons') ?: null;
            }

            $dto = new HomeDTO(
                image: $imagePath,
                header: $validated['header'] ?? null,
                description: $validated['description'] ?? null,
                buttons: $buttons
            );

            return response()->json($this->home->create($dto));
        });
    }

    public function update(Request $req, $homeId)
    {
        return $this->dbTransaction(function () use ($req, $homeId) {
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

            $imagePath = $this->storeBase64Image($validated['image'] ?? '', 'home_images') ?: $home->image;

            $buttons = $validated['buttons'] ?? [];
            foreach ($buttons as &$button) {
                $button['icon'] = $this->storeBase64Image($button['icon'] ?? '', 'home_icons') ?: null;
            }

            $dto = new HomeDTO(
                image: $imagePath,
                header: $validated['header'] ?? null,
                description: $validated['description'] ?? null,
                buttons: $buttons
            );

            return response()->json($this->home->update($homeId, $dto));
        });
    }

    public function delete($homeId)
    {
        $this->home->delete($homeId);
        return response()->json(['message' => 'Deleted successfully']);
    }

}
