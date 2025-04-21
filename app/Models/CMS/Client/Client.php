<?php

namespace App\Models\CMS\Client;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasRandomId;

    protected $table = 'clients';
    protected $primaryKey = 'clientId';
    protected $fillable = [
        'image', 'name',
    ];

}
