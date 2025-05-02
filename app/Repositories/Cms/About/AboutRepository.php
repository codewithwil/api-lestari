<?php

namespace App\Repositories\Cms\About;

use App\{
    DTOs\Cms\About\AboutDTO,
    Models\CMS\About\About,
    Repositories\Cms\About\AboutRepositoryInterface,
};

class AboutRepository implements AboutRepositoryInterface
{
    public function getAll()
    {
        return About::select('aboutId', 'image', 'header', 'desc')
        ->with(['aboutContent' => function ($query) {
            $query->select('aboutConId', 'about_id', 'title', 'desc');
        }])
        ->orderBy('created_at', 'asc') 
        ->get();
    }

    public function find(string $aboutId)
    {
        return About::with('aboutContent')->findOrFail($aboutId);
    }

    public function create(AboutDTO $data)
    {
        $about = About::create([
            'image' => $data->image,
            'header' => $data->header,
            'desc'  => $data->desc,
        ]);
        foreach ($data->abouts as $aboutContent) {
            $about->aboutcontent()->create($aboutContent);
        }

        return $about->load('aboutcontent');
    }

    public function update(string $aboutId, AboutDTO $data)
    {
        $about = About::findOrFail($aboutId);
        $about->update([
            'image' => $data->image,
            'header' => $data->header,
            'desc'  => $data->desc,
        ]);

        $about->aboutcontent()->delete(); 
        foreach ($data->abouts as $aboutContent) {
            $about->aboutcontent()->create($aboutContent);
        }

        return $about->load('aboutContent');
    }

    public function delete(string $aboutId)
    {
        $about = About::findOrFail($aboutId);
        $about->aboutcontent()->delete();
        $about->delete();
    }
}
