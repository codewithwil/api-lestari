<?php

namespace App\Models\CMS\Testimonial;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    
    protected $table = 'testimonials';
    protected $primaryKey = 'testimonialId';
    protected $fillable = [
        'header', 'desc'
    ];

    // public function serviceContent()
    // {
    //     return $this->hasMany(ServiceContent::class, 'service_id', 'serviceId');
    // }
}
