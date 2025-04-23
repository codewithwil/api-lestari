<?php

namespace App\Http\Controllers\API\CMS\Client;

use App\{
    DTOs\Cms\Client\ClientDto,
    Http\Controllers\Controller,
    Repositories\Cms\Client\ClientRepositoryInterface,
};

use Illuminate\Http\Request;

class ClientC extends Controller
{
    public function __construct(
        protected ClientRepositoryInterface $client
    ) {}

    public function index()
    {
        return response()->json($this->client->getAll());
    }

    public function show($clientId)
    {
        return response()->json($this->client->find($clientId));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('client_images', 'public');
        }

        $dto = new ClientDto(
            image:  $imagePath,
            name: $validated['name'] ?? null,
        );

        return response()->json($this->client->create($dto));
    }

    public function update(Request $request, $clientId)
    {
        $validated = $request->validate([
            'name'  => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $client = $this->client->find($clientId);

        $imagePath = $client->image; 
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('client_images', 'public');
        }

        $dto = new ClientDto(
            image:  $imagePath,
            name: $validated['name'] ?? null,
        );

        return response()->json($this->client->update($clientId, $dto));
    }

    public function destroy($clientId)
    {
        $this->client->delete($clientId);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
