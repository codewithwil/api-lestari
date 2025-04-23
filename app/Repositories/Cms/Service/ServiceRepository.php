<?php

namespace App\Repositories\Cms\Service;

use App\{
    DTOs\Cms\Service\ServiceDto,
    Models\CMS\Service\Service
};

class ServiceRepository implements ServiceRepositoryInterface
{
    public function getAll()
    {
        return Service::with('serviceContent')->get();
    }

    public function find(string $serviceId)
    {
        return Service::with('serviceContent')->findOrFail($serviceId);
    }

    public function create(ServiceDto $data)
    {
        $service = Service::create([
            'header' => $data->header,
            'desc'  => $data->desc,
        ]);

        foreach ($data->services as $button) {
            $service->serviceContent()->create($button);
        }

        return $service->load('serviceContent');
    }

    public function update(string $serviceId, ServiceDto $data)
    {
        $service = Service::findOrFail($serviceId);
        $service->update([
            'header' => $data->header,
            'desc'  => $data->desc,
        ]);

        $service->serviceContent()->delete();
        foreach ($data->services as $button) {
            $service->serviceContent()->create($button);
        }

        return $service->load('serviceContent');
    }

    public function delete(string $serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->delete();
    }
}
