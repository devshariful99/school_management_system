<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteSetting extends BaseModel
{
    use HasFactory;
    protected $fillable = ['key', 'value', 'env_key'];
}
