<?php

namespace App\Models\CMS\About;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class AboutContent extends Model
{
    use HasRandomId;

    protected $table = 'about_contents';
    protected $primaryKey = 'aboutConId';
    protected $fillable = [
        'about_id', 'title', 'desc'
    ];

    public function about()
    {return $this->belongsTo(About::class);}
}
