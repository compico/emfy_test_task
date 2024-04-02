<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Phpmig\Migration\Migration;

class AddLeadTable extends Migration
{
    protected string $tableName;
    protected Builder $schema;

    public function init()
    {
        $this->tableName = 'lead';
        $this->schema = $this->get('db')->schema();
    }

    public function up()
    {
        $this->schema->create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->integer('account_id');
            $table->string('name');
            $table->integer('pipeline_id');
            $table->integer('status_id');
            $table->integer('price');
            $table->integer('responsible_user_id');
            $table->integer('last_modified');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            $table->unique(['account_id', 'lead_id'], 'unique_account_id_lead_id');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $this->schema->drop($this->tableName);
    }
}
