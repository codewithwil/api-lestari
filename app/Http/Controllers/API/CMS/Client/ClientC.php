<?php

namespace App\Http\Controllers\API\CMS\Client;

use App\{
    DTOs\Cms\Client\ClientDto,
    Http\Controllers\Controller,
    Repositories\Cms\Client\ClientRepositoryInterface,
    Traits\Base64FileHandler,
    Traits\dbTransac
};

use Illuminate\Http\Request;

class ClientC extends Controller
{
    use dbTransac,Base64FileHandler;

    public function __construct(
        protected ClientRepositoryInterface $client
    ) {}

    public function index()
    {
        return response()->json($this->client->getAll());
    }

    public function show($clientId)
    {
        $client = $this->client->find($clientId);

        if ($client->image) {
            $client->image = $this->convertToBase64(storage_path("app/public/{$client->image}"));
        }
        return response()->json($client);
    }

    public function store(Request $req)
    {
        return $this->dbTransaction(function () use ($req) {
            $validated = $req->validate([
                'name'        => 'nullable|string',
                'image'         => 'nullable|string',
            ]);

            $imagePath = $this->storeBase64Image($validated['image'] ?? '', 'client_images') ?: null;

            $dto = new ClientDto(
                image:  $imagePath,
                name: $validated['name'] ?? null,
            );

            return response()->json($this->client->create($dto));
        });
    }

    public function update(Request $req, $clientId)
    {
        return $this->dbTransaction(function () use ($req, $clientId) {
            $validated = $req->validate([
                'name'  => 'nullable|string',
                'image' => 'nullable|string',
            ]);

            $client = $this->client->find($clientId);
            $imagePath = $this->storeBase64Image($validated['image'] ?? '', 'client_images') ?: $client->image;

            $dto = new ClientDto(
                image:  $imagePath,
                name: $validated['name'] ?? null,
            );

            return response()->json($this->client->update($clientId, $dto));
        });
    }

    public function delete($clientId)
    {
        return $this->dbTransaction(function () use ($clientId) {
            $this->client->delete($clientId);
            return response()->json(['message' => 'Deleted successfully']);
        });
    }
}
