<?php

namespace App\View\Components;

use App\Helpers\ApplicationTableHelper;
use App\Helpers\BadgeHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    /**
     * Create a new component instance.
     */

    public string $label;
    public string $type;
    public string $class;

    public function __construct(string $label, string $type = 'status')
    {
        //

        $this->label = $label;
        $this->type = $type;

        $this->class = $this->resolveClass($label, $type);
    }

    private function resolveClass($label, $type) {
        return match($type) {

            'status' => ApplicationTableHelper::statusClass($label),
            'role' => ApplicationTableHelper::roleClass($label),
            default => 'bg-gray-100 text-gray-800',

        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.badge');
    }
}
