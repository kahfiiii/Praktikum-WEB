<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Thumbnail materi: kotak berwarna + ikon dokumen.
 * Warna mengikuti urutan kartu di desain Figma (coral → primary → lime).
 */
class MaterialThumb extends Component
{
    public function __construct(
        public string $type = 'PDF',
        public int $index = 0,
        public string $size = 'md',
    ) {}

    public function toneClasses(): string
    {
        return match ($this->index % 3) {
            0 => 'bg-[#ffe5e1] text-[#a9443a]',
            1 => 'bg-[#e4e7ff] text-primary',
            default => 'bg-[#edfabd] text-lime-ink',
        };
    }

    public function boxClasses(): string
    {
        return $this->size === 'lg'
            ? 'h-16 w-16 rounded-[16px]'
            : 'h-14 w-14 rounded-[14px]';
    }

    public function iconClasses(): string
    {
        return $this->size === 'lg' ? 'h-7 w-7' : 'h-6 w-6';
    }

    public function render(): View
    {
        return view('components.material-thumb');
    }
}
