<?php

declare(strict_types=1);

namespace Masqueradis\Migration\Migrations;

use Masqueradis\Migration\Migrations\Interface\MigrationInterface;

class QueryBuilder
{
    protected $columns = [];
    protected $wheres = [];

    public function __construct(
        protected \PDO $pdo,
        protected string $table,
        protected MigrationInterface $grammar,
    ) {}

    public function select(array $columns = ['*']): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function where(string $column, string $operator, $value): self
    {
        $this->wheres[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function get()
    {
        $sql = $this->grammar->compileSelect($this->table, $this->columns, $this->wheres);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_column($this->wheres, 'value'));
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete()
    {
        $sql = $this->grammar->compileDelete($this->table, $this->wheres);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_column($this->wheres, 'value'));
    }

    public function insert(array $values)
    {
        $sql = $this->grammar->compileInsert($this->table, $values);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($values));
    }
}
