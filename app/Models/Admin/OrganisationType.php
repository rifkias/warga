<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
}
