<?php
namespace App\Models\Product;

use App\Models\Attribute\AttributeFactory;
use App\Models\Attribute\Attribute;

abstract class Product
{
    protected string $id;
    protected string $name;
    protected float $price;
    protected string $category;
    protected bool $inStock;
    protected array $gallery;
    protected array $attributes = [];
    protected string $description;



    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->price = isset($data['price']) ? (float) $data['price'] : 0.00;
        $this->category = $data['category'];
        $this->inStock = $data['in_stock'] ?? true;
        $this->gallery = $data['gallery'] ?? [];
        $this->description = $data['description'];



        // optionally log
        if (!isset($data['category'])) {
            error_log("⚠️ Category missing, defaulting to 'tech' for product: " . ($data['id'] ?? 'unknown'));
        }
        

        $this->attributes = [];

        $attributeDataList = [];

        if (!empty($data['attributes']) && is_string($data['attributes'])) {
            $decoded = json_decode($data['attributes'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $attributeDataList = $decoded;
            }
        }
        
        foreach ($attributeDataList as $attrData) {
            try {
                $this->attributes[] = AttributeFactory::create($attrData);
            } catch (\Throwable $e) {
                // Optional: log or skip invalid attributes
            }
        }
    }

    abstract public function getType(): string;
    abstract public function getDisplayAttributes(): array;

    // Getters
    public function getId(): string {
        if (empty($this->id)) {
            error_log("❌ getId() returning null or empty for class: " . static::class);
        }
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
    public function getCategory(): string { return $this->category; }
    public function isInStock(): bool { return $this->inStock; }
    public function getGallery(): array { return $this->gallery; }


    /** @return Attribute[] */
    public function getAttributes(): array { return $this->attributes; }

    public function setAttributes(array $attributes): void {
        $this->attributes = $attributes;
    }


    /** ✅ Add this to support GraphQL-ready data */
    public function getFormattedAttributes(): array
    {
        return array_map(fn($attr) => $attr->formatForDisplay(), $this->attributes);
    }
}
