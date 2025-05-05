<?php
declare(strict_types=1);

namespace App\Models\Attribute;

abstract class Attribute
{
    protected string $name;
    protected string $type;
    protected array $items;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->items = $data['items'] ?? [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    abstract public function formatForDisplay(): array;
}
