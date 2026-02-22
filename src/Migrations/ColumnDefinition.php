<?php

declare(strict_types=1);

namespace Masqueradis\Migration\Migrations;

class ColumnDefinition
{
    public array $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function nullable(bool $value = true): self
    {
        $this->attributes['nullable'] = $value;
        return $this;
    }

    public function default($value): self
    {
        $this->attributes['default'] = $value;
        return $this;
    }
}
