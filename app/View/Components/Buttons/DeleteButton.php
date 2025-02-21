<?php

namespace App\View\Components\Buttons;

use Illuminate\View\Component;

class DeleteButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $route,
        public string $confirmMessage = 'Are you sure you want to delete this item?'
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('Components.Buttons.Delete-Button');
    }
}
