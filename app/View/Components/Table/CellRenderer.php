<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CellRenderer extends Component
{
    /**
     * Create a new component instance.
     */
    public $key;
    public $value;
    public $row;
    public $type;



    public function __construct($key, $value, $row = [], $type)
    {
        //
        $this->key = $key;
        $this->value = $value;
        $this->row = $row;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.cell-renderer');
    }
}
