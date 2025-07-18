<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VpnClient extends Model
{

    protected $connection = 'mysql';
    protected $table = 'mpmo.vpn_clients';  
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'public_key',
        'private_key',
        'address'];

    // Encrypt the private key at rest
    protected $casts = [
        'private_key' => 'encrypted',
    ];
}
