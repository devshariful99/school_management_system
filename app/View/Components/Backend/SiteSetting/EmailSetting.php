<?php

namespace App\View\Components\Backend\SiteSetting;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EmailSetting extends Component
{
    public $email_settings;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->email_settings = SiteSetting::whereIn('key', ['mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encription', 'mail_from', 'mail_from_name'])->pluck('value', 'key')->all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backend.site-setting.email-setting');
    }
}
