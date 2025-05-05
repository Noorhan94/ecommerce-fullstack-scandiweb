<?php
declare(strict_types=1);

namespace App\Models\Attribute;

class AttributeFactory
{
    public static function create(array $data): Attribute
    {
        return match (strtolower($data['type'] ?? '')) {
            'swatch' => new SwatchAttribute($data),
            'text' => new TextAttribute($data),
            default => throw new \InvalidArgumentException("Unsupported attribute type: " . $data['type'])
        };
    }
}
