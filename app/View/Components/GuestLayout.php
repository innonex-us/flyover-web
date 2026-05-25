<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $meta_description = null,
        public ?string $meta_keywords = null,
        public ?string $meta_image = null,
        public ?string $canonical_url = null,
        public ?string $og_type = null,
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
