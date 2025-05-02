<?php

namespace App\Http\Controllers\API\CMS\About;

use App\{
    DTOs\Cms\About\AboutDTO,
    Http\Controllers\Controller,
    Repositories\Cms\About\AboutRepositoryInterface,
    Traits\Base64FileHandler,
    Traits\dbTransac,
};

use Illuminate\Http\Request;

class AboutC extends Controller
{
    use dbTransac, Base64FileHandler;

    public function __construct(
        protected AboutRepositoryInterface $about
    ) {}

    public function index()
    {
        return response()->json($this->about->getAll());
    }

    public function show($aboutId)
    {
        $about = $this->about->find($aboutId);

        if ($about->image) {
            $about->image = $this->convertToBase64(storage_path("app/public/{$about->image}"));
        }

        return response()->json($about);
    }

    public function store(Request $req)
    {
        return $this->dbTransaction(function () use ($req) {
            $validated = $req->validate([
                'desc'           => 'nullable|string',
                'header'         => 'nullable|string',
                'image'          => 'nullable|string',
                'abouts'         => 'array',
                'abouts.*.title' => 'nullable|string|max:50',
                'abouts.*.desc'  => 'nullable|string|max:255',
            ]);

            $imagePath = $this->storeBase64Image($validated['image'] ?? '', 'about_images') ?: null;

            $dto = new AboutDTO(
                image:  $imagePath,
                header: $validated['header'] ?? null,
                desc:   $validated['desc'] ?? null,
                abouts: $validated['abouts'] ?? []
            );

            return response()->json($this->about->create($dto));
        });
    }

    public function update(Request $req, $aboutId)
    {
        return $this->dbTransaction(function () use ($req, $aboutId) {
            $validated = $req->validate([
                'desc'           => 'nullable|string',
                'header'         => 'nullable|string',
                'image'          => 'nullable|string',
                'abouts'         => 'array',
                'abouts.*.title' => 'nullable|string|max:50',
                'abouts.*.desc'  => 'nullable|string|max:255',
            ]);

            $about = $this->about->find($aboutId);

            $imagePath = $this->storeBase64Image($validated['image'] ?? '', 'about_images') ?: $about->image;

            $dto = new AboutDTO(
                image:  $imagePath,
                header: $validated['header'] ?? null,
                desc:   $validated['desc'] ?? null,
                abouts: $validated['abouts'] ?? []
            );

            return response()->json($this->about->update($aboutId, $dto));
        });
    }

    public function delete($aboutId)
    {
        return $this->dbTransaction(function () use ($aboutId) {
            $this->about->delete($aboutId);
            return response()->json(['message' => 'Deleted successfully']);
        });
    }
}
