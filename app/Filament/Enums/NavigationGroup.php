<?php

namespace App\Filament\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;

enum NavigationGroup implements HasLabel, HasIcon
{
    case DANH_MUC;
     public function getLabel(): string
    {
        return match ($this) {
            self::DANH_MUC => "Danh mục",
            default => '',
        };
    }

     public function getIcon(): ?string
    {
        return match ($this) {
            self::DANH_MUC => 'heroicon-s-folder',
            default => null,
        };
    }
}