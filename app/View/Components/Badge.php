<?php

namespace App\View\Components;

use App\Helpers\ApplicationDisplayHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    /**
     * Create a new component instance.
     */

    public string $label;
    public string $class;

    public function __construct($label)
    {
        //

        $this->label = $label ?? 'test';

        $this->class = $this->resolveClass($label);
    }

    private function resolveClass($label) {
        return ApplicationDisplayHelper::renderBadgeClass($label);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.badge');
    }
}
