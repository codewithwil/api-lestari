<?php

namespace App\Models\CMS\Home;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class HomeButtons extends Model
{
    use HasRandomId;

    protected $table = 'home_buttons';
    protected $primaryKey = 'homeButtonId';
    protected $fillable = [
        'home_id', 'text', 'link'
    ];

    public function home()
    {
        return $this->belongsTo(Home::class);
    }
}
