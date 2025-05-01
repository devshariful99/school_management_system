<?php

namespace App\View\Components\Backend\SiteSetting;

use App\Models\EmailTemplate;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EmailTemplateSetting extends Component
{
    public $email_templates;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->email_templates = EmailTemplate::latest()->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backend.site-setting.email-template-setting');
    }
}
