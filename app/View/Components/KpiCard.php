<?php

namespace App\View\Components;

use Illuminate\View\Component;

class KpiCard extends Component
{
    public $title;
    public $value;
    public $prefix;

    public function __construct($title, $value, $prefix = '')
    {
        $this->title = $title;
        $this->value = $value;
        $this->prefix = $prefix;
    }

    public function render()
    {
        return view('components.kpi-card');
    }
}
