<?php

namespace App\View\Components\Backend\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public $datas;
    /**
     * Create a new component instance.
     */
    public function __construct($datas)
    {
        $this->datas = $datas;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backend.admin.button');
    }
}