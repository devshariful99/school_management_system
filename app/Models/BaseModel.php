<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use HasFactory, SoftDeletes;


    public function created_admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function updated_admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function deleted_admin()
    {
        return $this->belongsTo(Admin::class, 'deleted_by');
    }

    public function creater()
    {
        return $this->morphTo();
    }
    public function updater()
    {
        return $this->morphTo();
    }
    public function deleter()
    {
        return $this->morphTo();
    }

    public function getStatusBadgeTitle()
    {
        if ($this->status == 1) {
            return 'Active';
        } else {
            return 'Deactive';
        }
    }
    public function getStatusBtnTitle()
    {
        if ($this->status == 1) {
            return 'Deactive';
        } else {
            return 'Active';
        }
    }

    public function getStatusBtnBg()
    {
        if ($this->status == 1) {
            return 'btn-success';
        } else {
            return 'btn-danger';
        }
    }
    public function getStatusBadgeBg()
    {
        if ($this->status == 1) {
            return 'badge badge-success';
        } else {
            return 'badge badge-warning';
        }
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}