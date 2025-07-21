<?php

namespace App\View\Components\Table;

use App\Helpers\ApplicationTableHelper;
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



    public function __construct($value)
    {
        //
        $this->value = $value;


    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $badge = ApplicationTableHelper::renderCellBadge($this->value);

        return view('components.table.cell-renderer', [
            'badge' => $badge,
            'value' => $this->value
        ]);
    }
}
