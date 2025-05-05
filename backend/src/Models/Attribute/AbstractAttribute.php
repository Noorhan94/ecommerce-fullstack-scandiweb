<?php
declare(strict_types=1);

namespace App\Models\Attribute;

abstract class AbstractAttribute
{
    protected string $name;
    protected string $type;
    protected array $items;

    public function __construct(string $name, string $type, array $items)
    {
        $this->name = $name;
        $this->type = $type;
        $this->items = $items;
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

    public function formatForDisplay(): array
    {
        return [
            'name' => $this->getName(),
            'type' => $this->getType(),
            'items' => $this->getItems()
        ];
    }
}
