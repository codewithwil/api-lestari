<?php

namespace App\Repositories\Cms\Client;

use App\DTOs\Cms\Client\ClientDto;

interface ClientRepositoryInterface
{
    public function getAll();
    public function find(string $clientId);
    public function create(ClientDto $data);
    public function update(string $clientId, ClientDto $data);
    public function delete(string $clientId);
}
