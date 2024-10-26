<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;
use OwenIt\Auditing\Contracts\Auditable;

class Permission extends SpatiePermission implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    public function creater_admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function updater_admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function deleter_admin()
    {
        return $this->belongsTo(Admin::class, 'deleted_by');
    }
}
