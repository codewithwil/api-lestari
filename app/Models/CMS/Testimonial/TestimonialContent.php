<?php

namespace App\Models\CMS\Testimonial;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class TestimonialContent extends Model
{
    use HasRandomId;

    protected $table = 'testimonial_contents';
    protected $primaryKey = 'testimonialConId';
    protected $fillable = [
        'testimonial_id','image', 'name', 'desc'
    ];

    public function testimonials(){return $this->belongsTo(Testimonial::class, 'testimonial_id', 'testimonialId');}
}
