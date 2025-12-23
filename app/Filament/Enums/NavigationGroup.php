<?php

namespace App\Filament\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum NavigationGroup implements HasIcon, HasLabel
{
    case DANH_MUC;
    case NGUOI_DUNG;

    public function getLabel(): string
    {
        return match ($this) {

            self::DANH_MUC => 'Danh mục',
            self::NGUOI_DUNG => 'Người dùng',
            default => '',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DANH_MUC => 'heroicon-s-folder',
            self::NGUOI_DUNG => 'heroicon-s-user',
            default => null,
        };
    }
}
