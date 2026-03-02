<?php

declare(strict_types=1);

namespace Masqueradis\Migration\Migrations\Versions;

use Masqueradis\Migration\Migrations\Migration;
use Masqueradis\Migration\Migrations\Schema;
use Masqueradis\Migration\Migrations\Blueprint;

class CreateProductTable extends Migration
{
    public function up(Schema $schema): void
    {
        $schema->create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->string('status');
        });
    }

    public function down(Schema $schema): void
    {
        $schema->drop('product');
    }
}