<?php

namespace App\Http\Controllers\API\CMS\CountUp;

use App\{
    Http\Controllers\Controller,
    DTOs\Cms\CountUp\CountUpDto,
    Repositories\Cms\CountUp\CountUpRepositoryInterface,
};

use Illuminate\Http\Request;

class CountUpC extends Controller
{
    public function __construct(
        protected CountUpRepositoryInterface $countUp
    ) {}

    public function index()
    {
        return response()->json($this->countUp->getAll());
    }

    public function show($countUpId)
    {
        return response()->json($this->countUp->find($countUpId));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('icon')) {
            $imagePath = $request->file('icon')->store('countUp_images', 'public');
        }

        $dto = new CountUpDto(
            icon:  $imagePath,
            title: $validated['title'] ?? null,
        );

        return response()->json($this->countUp->create($dto));
    }

    public function update(Request $request, $countUpId)
    {
        $validated = $request->validate([
            'title'  => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ]);

        $countUp = $this->countUp->find($countUpId);

        $imagePath = $countUp->icon; 
        if ($request->hasFile('icon')) {
            $imagePath = $request->file('icon')->store('countUp_images', 'public');
        }

        $dto = new CountUpDto(
            icon:  $imagePath,
            title: $validated['title'] ?? null,
        );

        return response()->json($this->countUp->update($countUpId, $dto));
    }

    public function destroy($countUpId)
    {
        $this->countUp->delete($countUpId);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
