<?php

namespace App\View\Components\Backend\SiteSetting;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GeneralSetting extends Component
{
    public $general_settings;
    public $available_timezones;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->general_settings = SiteSetting::whereIn('key', ['site_name', 'site_short_name', 'timezone', 'site_logo', 'site_favicon', 'env', 'debug', 'debugbar', 'date_format', 'time_format'])->pluck('value', 'key')->all();
        $this->available_timezones = availableTimezones();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backend.site-setting.general-setting');
    }
}
