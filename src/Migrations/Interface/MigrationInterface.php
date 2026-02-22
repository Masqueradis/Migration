<?php

declare(strict_types=1);

namespace Masqueradis\Migration\Migrations\Interface;

use Masqueradis\Migration\Migrations\Blueprint;

interface MigrationInterface
{
    public function compileCreate(Blueprint $blueprint): string;
    public function compileDrop(string $table): string;
    public function compileSelect(string $table, array $columns, array $wheres): string;
    public function compileDelete(string $table, array $wheres): string;
    public function compileInsert(string $table, array $values): string;
}
