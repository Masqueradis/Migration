<?php

declare(strict_types=1);

namespace Masqueradis\Migration\Migrations;

abstract class BaseGrammar
{
    abstract public function wrap(string $value): string;

    protected function getModifiers(ColumnDefinition $column): string
    {
        $sql = $column->nullable ? ' NULL ' : ' NOT NULL ';
        if ($column->default !== null) {
            $defaultValue = $column->default;
            if (is_string($defaultValue)) {
                $defaultValue = "'{$defaultValue}'";
            } elseif (is_bool($defaultValue)) {
                $defaultValue = $defaultValue ? 'TRUE' : 'FALSE';
            }
            $sql .= " DEFAULT {$defaultValue}";
        }
        return $sql;
    }

    public function compileInsert(string $table, array $values): string
    {
        $columns = implode(', ', array_map([$this, 'wrap'], array_keys($values)));

        $placeholders = implode(', ', array_fill(0, count($values), '?'));

        return "INSERT INTO {$this->wrap($table)} ({$columns}) VALUES ({$placeholders})";
    }
}
