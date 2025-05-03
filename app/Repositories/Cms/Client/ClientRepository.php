<?php

namespace App\Repositories\Cms\Client;

use App\{
    DTOs\Cms\Client\ClientDto,
    Models\CMS\Client\Client,
};

class ClientRepository implements ClientRepositoryInterface
{
    public function getAll()
    {
        return Client::select('clientId', 'image', 'name')
        ->orderBy('created_at', 'asc') 
        ->get();
    }

    public function find(string $clientId)
    {
        return Client::findOrFail($clientId);
    }

    public function create(ClientDto $data)
    {
        $client = Client::create([
            'image' => $data->image,
            'name'  => $data->name,
        ]);


        return $client;
    }

    public function update(string $clientId, ClientDto $data)
    {
        $client = Client::findOrFail($clientId);
        $client->update([
            'image' => $data->image,
            'name'  => $data->name,
        ]);

        return $client;
    }

    public function delete(string $clientId)
    {
        $client = Client::findOrFail($clientId);
        $client->delete();
    }
}
