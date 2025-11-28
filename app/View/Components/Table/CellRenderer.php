<?php

namespace App\View\Components\Table;

use App\Helpers\ApplicationDisplayHelper;
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
    public $isBold;
    public $index;



    public function __construct($value, $index = null, $isBold = false)
    {
        //
        $this->value = $value;
        $this->index = $index;
        $this->isBold = $isBold;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $badge = ApplicationDisplayHelper::renderCellBadge($this->value);

        return view('components.table.cell-renderer', [
            'badge' => $badge,
            'value' => $this->value
        ]);
    }
}
