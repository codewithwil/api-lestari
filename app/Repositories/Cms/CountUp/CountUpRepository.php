<?php

namespace App\Repositories\Cms\CountUp;

use App\{
    DTOs\Cms\CountUp\CountUpDto,
    Models\CMS\CountUp\CountUp
};

class CountUpRepository implements CountUpRepositoryInterface
{
    public function getAll()
    {
        return CountUp::get();
    }

    public function find(string $countUpId)
    {
        return CountUp::findOrFail($countUpId);
    }

    public function create(CountUpDto $data)
    {
        $client = CountUp::create([
            'icon' => $data->icon,
            'title'  => $data->title,
        ]);
        return $client;
    }

    public function update(string $countUpId, CountUpDto $data)
    {
        $client = CountUp::findOrFail($countUpId);
        $client->update([
            'icon' => $data->icon,
            'title'  => $data->title,
        ]);

        return $client;
    }

    public function delete(string $countUpId)
    {
        $client = CountUp::findOrFail($countUpId);
        $client->delete();
    }
}
