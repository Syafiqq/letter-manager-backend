<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLetterTable extends Migration
{
    /**
     * @var string
     */
    static $tableName = \App\Eloquents\Letter::table;
    /**
     * @var Illuminate\Database\Schema\Builder
     */
    private $schema;

    /**
     * CreateLettersTable constructor.
     */
    public function __construct()
    {
        $this->schema = \Illuminate\Support\Facades\Schema::getFacadeRoot();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!$this->schema->hasTable(self::$tableName))
        {
            $this->schema->create(self::$tableName, function (Blueprint $table) {
                $table->uuid('id');
                $table->string('title');
                $table->string('code');
                $table->string('index');
                $table->string('number');
                $table->string('subject');
                $table->dateTime('date');
                $table->enum('kind', \App\Eloquents\Letter::letterKind);
                $table->string('file');
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
                $table->uuid('issuer');
                $table->primary('id');
                $table->foreign('issuer')
                    ->references('id')->on(CreateUsersTable::$tableName)
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }
        else
        {
            echo 'Table Already Exists';
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists(self::$tableName);
    }
}
