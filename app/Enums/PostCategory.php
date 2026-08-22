<?php

namespace App\Enums;

enum PostCategory: string
{
    case CaseStudy = 'case-study';
    case Technical = 'technical';
    case News = 'news';
    case Insights = 'insights';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::CaseStudy => 'CASE STUDY',
            self::Technical => 'TECHNICAL',
            self::News => 'NEWS',
            self::Insights => 'INSIGHTS',
        };
    }

    /**
     * Slug for URL filtering.
     */
    public function slug(): string
    {
        return $this->value;
    }

    /**
     * All available categories as [value => label] for select options.
     */
    public static function options(): array
    {
        return array_map(fn (self $case) => $case->label(), self::cases());
    }
}
