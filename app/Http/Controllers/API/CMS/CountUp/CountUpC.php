<?php

namespace App\Http\Controllers\API\CMS\CountUp;

use App\{
    Http\Controllers\Controller,
    DTOs\Cms\CountUp\CountUpDto,
    Repositories\Cms\CountUp\CountUpRepositoryInterface,
    Traits\Base64FileHandler,
    Traits\dbTransac
};

use Illuminate\Http\Request;

class CountUpC extends Controller
{
    use dbTransac, Base64FileHandler;
    public function __construct(
        protected CountUpRepositoryInterface $countUp
    ) {}

    public function index()
    {
        return response()->json($this->countUp->getAll());
    }

    public function show($countUpId)
    {
        $countUp = $this->countUp->find($countUpId);
        if ($countUp->icon) {
            $countUp->icon = $this->convertToBase64(storage_path("app/public/{$countUp->icon}"));
        }
        return response()->json($countUp);
    }

    public function store(Request $req)
    {
        return $this->dbTransaction(function () use ($req) {
            $validated = $req->validate([
                'title'  => 'required|string',
                'icon'   => 'required|string',
                'amount' => 'required|integer',
            ]);

            $imagePath = $this->storeBase64Image($validated['icon'] ?? '', 'countUp_images') ?: null;

            $dto = new CountUpDto(
                icon:  $imagePath,
                title: $validated['title'] ?? null,
                amount: $validated['amount'] ?? null,
            );

            return response()->json($this->countUp->create($dto));
        });
    }

    public function update(Request $req, $countUpId)
    {
        return $this->dbTransaction(function () use ($req, $countUpId) {
            $validated = $req->validate([
                'title'  => 'nullable|string',
                'icon'   => 'nullable|string',
                'amount' => 'nullable|integer',
            ]);

            $countUp = $this->countUp->find($countUpId);

            $imagePath = $this->handleUpdatedImage($countUp['icon'] ?? '', $countUp?->icon, 'countUp_images');

            $dto = new CountUpDto(
                icon:  $imagePath,
                title: $validated['title'] ?? null,
                amount: $validated['amount'] ?? null,
            );

            return response()->json($this->countUp->update($countUpId, $dto));
        });
    }

    public function delete($countUpId)
    {
        $this->countUp->delete($countUpId);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
