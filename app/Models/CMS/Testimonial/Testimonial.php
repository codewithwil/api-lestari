<?php

namespace App\Models\CMS\Testimonial;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasRandomId;
    protected $table = 'testimonials';
    protected $primaryKey = 'testimonialId';
    protected $fillable = [
        'header', 'desc'
    ];

    public function TestimonialContent(){return $this->hasMany(TestimonialContent::class, 'testimonial_id', 'testimonialId');}
}
