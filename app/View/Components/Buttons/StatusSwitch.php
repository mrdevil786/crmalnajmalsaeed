<?php

declare(strict_types=1);

namespace App\View\Components\Buttons;

use Illuminate\View\Component;

class StatusSwitch extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public int $userId,
        public string $status = 'active',
        public string $updateUrl = '',
        public string $csrfToken = ''
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('Components.Buttons.Status-Switch');
    }
}
