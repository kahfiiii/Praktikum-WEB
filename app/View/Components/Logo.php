<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Logo extends Component
{
    public function __construct(
        public string $size = 'md',
        public bool $showWordmark = true,
        public bool $inverse = false,
    ) {}

    public function wrapperSize(): string
    {
        return match ($this->size) {
            'sm' => 'size-[30px]',
            'lg' => 'size-[46px]',
            default => 'size-[38px]',
        };
    }

    public function boxSize(): string
    {
        return match ($this->size) {
            'sm' => 'size-[26px] rounded-bl-[7px] rounded-br-[10px] rounded-tl-[9px] rounded-tr-[7px]',
            'lg' => 'size-[42px] rounded-bl-[11px] rounded-br-[17px] rounded-tl-[15px] rounded-tr-[12px]',
            default => 'size-[35px] rounded-bl-[9px] rounded-br-[14px] rounded-tl-[12px] rounded-tr-[10px]',
        };
    }

    public function letterSize(): string
    {
        return match ($this->size) {
            'sm' => 'text-[16px]',
            'lg' => 'text-[25px]',
            default => 'text-[21px]',
        };
    }

    public function shadowOffset(): string
    {
        return match ($this->size) {
            'sm' => '2px 2px',
            'lg' => '3.5px 3.5px',
            default => '3px 3px',
        };
    }

    public function textSize(): string
    {
        return match ($this->size) {
            'sm' => 'text-[16px] leading-[24px]',
            'lg' => 'text-[24px] leading-[34px]',
            default => 'text-[20px] leading-[30px]',
        };
    }

    public function render(): View
    {
        return view('components.logo');
    }
}
