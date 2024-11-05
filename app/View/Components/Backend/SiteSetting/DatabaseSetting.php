<?php

namespace App\View\Components\Backend\SiteSetting;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DatabaseSetting extends Component
{
    public $db_settings;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->db_settings = SiteSetting::whereIn('key', ['db_driver', 'db_host', 'db_port', 'db_name', 'db_username', 'db_password'])->pluck('value', 'key')->all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backend.site-setting.database-setting');
    }
}
