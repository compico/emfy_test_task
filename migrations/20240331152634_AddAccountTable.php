<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Phpmig\Migration\Migration;

class AddAccountTable extends Migration
{
    protected string $tableName;
    protected Builder $schema;

    public function init()
    {
        $this->tableName = 'account';
        $this->schema = $this->get('db')->schema();
    }

    public function up()
    {
        $this->schema->create($this->tableName, function (Blueprint $table) {
            $table->integer('account_id')->primary();
            $table->string('base_domain');
            $table->text('access_token');
            $table->text('refresh_token');
            $table->integer('expires_in');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        $this->schema->drop($this->tableName);
    }
}
