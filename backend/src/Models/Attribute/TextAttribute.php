<?php
declare(strict_types=1);

namespace App\Models\Attribute;

class TextAttribute extends Attribute
{
    public function __construct(array $data)
    {
        parent::__construct($data); // ✅ match parent signature
    }

    public function formatForDisplay(): array
    {
        return [
            'name' => $this->name,
            'type' => 'text',
            'items' => $this->items
        ];
    }
}
