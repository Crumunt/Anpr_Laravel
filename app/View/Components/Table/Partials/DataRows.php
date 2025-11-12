<?php

namespace App\View\Components\Table\Partials;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataRows extends Component
{
    /**
     * Create a new component instance.
     */
    public $rows;
    public function __construct($rows)
    {
        //
        $this->rows = $rows;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.partials.data-rows');
    }
}
