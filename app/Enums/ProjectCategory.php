<?php

namespace App\Enums;

enum ProjectCategory: string
{
    case Web = 'web';
    case Mobile = 'mobile';
    case Cloud = 'cloud';
    case Automation = 'automation';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Web => 'WEB',
            self::Mobile => 'MOBILE',
            self::Cloud => 'CLOUD',
            self::Automation => 'AUTOMATIZACIÓN',
        };
    }

    /**
     * All available categories as [value => label] for select options.
     */
    public static function options(): array
    {
        return array_map(fn (self $case) => $case->label(), self::cases());
    }
}
