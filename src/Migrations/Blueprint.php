<?php

declare(strict_types=1);

namespace Masqueradis\Migration\Migrations;

class Blueprint
{
    protected $columns = [];

    public function __construct(protected string $table) {}

    public function id(string $name = 'id'): ColumnDefinition
    {
        return $this->addColumn('id', $name);
    }

    public function string(string $name, int $length = 255): ColumnDefinition
    {
        return $this->addColumn('string', $name, ['$length' => $length]);
    }

    public function integer(string $name): ColumnDefinition
    {
        return $this->addColumn('integer', $name);
    }

    public function addColumn(string $type, string $name, array $params = []): ColumnDefinition
    {
        $column = new ColumnDefinition(array_merge([
            'type' => $type,
            'name' => $name,
            'nullable' => false,
        ], $params));
        $this->columns[] = $column;
        return $column;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
    public function getTable(): string
    {
        return $this->table;
    }
}
