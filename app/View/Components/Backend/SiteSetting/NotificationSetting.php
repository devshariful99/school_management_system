<?php

namespace App\View\Components\Backend\SiteSetting;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationSetting extends Component
{
    public $notification_settings;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->notification_settings = SiteSetting::whereIn('key', ['email_verification', 'sms_verification', 'user_registration'])->pluck('value', 'key')->all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backend.site-setting.notification-setting');
    }
}
