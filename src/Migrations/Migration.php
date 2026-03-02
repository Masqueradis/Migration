<?php

declare(strict_types=1);

namespace Masqueradis\Migration\Migrations;

abstract class Migration
{
    abstract public function up(Schema $schema): void;
    abstract public function down(Schema $schema): void;
}
