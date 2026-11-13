<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Dekorasi sparkle ✦ dari desain Figma.
 * Digambar sebagai SVG supaya konsisten lintas font/OS.
 */
class Sparkle extends Component
{
    public function __construct(
        public int $size = 130,
        public string $color = '#ffffff',
        public float $rotate = 0,
        public float $opacity = 0.75,
    ) {}

    public function render(): View
    {
        return view('components.sparkle');
    }
}
