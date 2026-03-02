<?php

declare(strict_types=1);

namespace Masqueradis\Migration\Migrations;

use Masqueradis\Migration\Migrations\Interface\MigrationInterface;
use PDO;

class Schema
{
    protected MigrationInterface $grammar;

    public function __construct(protected PDO $pdo)
    {
        $this->grammar = $this->resolveGrammar();
    }

    public function resolveGrammar(): MigrationInterface
    {
        $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        return match ($driver) {
            'mysql' => new MySqlGrammar(),
            'pgsql' => new PostgreSqlGrammar(),
            'mariadb' => new MariaDbGrammar(),
        };
    }

    public function create(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);

        $callback($blueprint);

        $sql = $this->grammar->compileCreate($blueprint);
        $this->pdo->exec($sql);

        printf("Created table '%s'\n", $table);
    }

    public function drop(string $table): void
    {
        $sql = $this->grammar->compileDrop($table);
        $this->pdo->exec($sql);
    }

    public function hasTable(string $table): bool
    {
        $sql = $this->grammar->compileTableExists($table);
        $stmt = $this->pdo->query($sql);

        return $stmt && $stmt->rowCount() > 0;
    }
}
