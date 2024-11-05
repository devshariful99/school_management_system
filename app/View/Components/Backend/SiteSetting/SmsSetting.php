<?php

namespace App\View\Components\Backend\SiteSetting;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SmsSetting extends Component
{
    public $sms_settings;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->sms_settings = SiteSetting::whereIn('key', ['api_url', 'api_key', 'api_secret', 'api_sender_id', 'api_status_code'])->pluck('value', 'key')->all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backend.site-setting.sms-setting');
    }
}
