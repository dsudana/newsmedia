<?php

namespace App\View\Components;

use App\Models\Ad;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdSlot extends Component
{
    public $placement;
    public $ads;

    /**
     * Create a new component instance.
     */
    public function __construct($placement)
    {
        $this->placement = $placement;
        $this->ads = Ad::where('placement', $placement)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if ($this->ads->isEmpty()) {
            return '';
        }
        return view('components.ad-slot');
    }
}
