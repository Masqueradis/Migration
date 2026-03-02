<?php

declare(strict_types=1);

namespace Masqueradis\Migration\Migrations;

use Masqueradis\Migration\Migrations\Interface\MigrationInterface;

class PostgreSqlGrammar extends BaseGrammar implements MigrationInterface
{
    public function wrap(string $value): string
    {
        return "\"$value\"";
    }

    public function compileCreate(Blueprint $blueprint): string
    {
        $columns = [];
        foreach ($blueprint->getColumns() as $column) {
            $type = match ($column->type) {
                'id' => 'SERIAL PRIMARY KEY ',
                'string' => 'VARCHAR(' . ($column->length ?: 255) . ')',
                'integer' => 'INTEGER ',
                default => 'TEXT ',
            };
            $columns[] = "{$this->wrap($column->name)} $type" . $this->getModifiers($column);
        }
        return "CREATE TABLE {$this->wrap($blueprint->getTable())} (" . implode(', ', $columns) . ");";
    }
    public function compileDrop(string $table): string
    {
        return "DROP TABLE {$this->wrap($table)};";
    }

    public function compileSelect(string $table, array $columns, array $wheres): string
    {
        $cols = empty($columns) ? '*' : implode(', ', array_map([$this, 'wrap'], $columns));
        $sql = "SELECT {$cols} FROM {$this->wrap($table)}";

        if (!empty($wheres)) {
            $sql .= ' WHERE ' . $this->compileWheres($wheres);
        }
        return $sql;
    }

    public function compileDelete(string $table, array $wheres): string
    {
        $sql = "DELETE FROM {$this->wrap($table)}";
        if (!empty($wheres)) {
            $sql .= ' WHERE ' . $this->compileWheres($wheres);
        }
        return $sql;
    }

    protected function compileWheres(array $wheres): string
    {
        return implode(' AND ', array_map(function ($w) {
            return "{$this->wrap($w['column'])} {$w['operator']} ?";
        }, $wheres));
    }

    public function compileTableExists(string $table): string
    {
        return "SELECT * FROM information_schema.tables 
        WHERE table_schema = 'public' AND table_name = '{$table}'";
    }
}
