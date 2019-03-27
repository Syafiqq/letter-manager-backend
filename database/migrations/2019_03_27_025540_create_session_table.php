<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionTable extends Migration
{
    /**
     * @var string
     */
    static $tableName = 'sessions';
    /**
     * @var Illuminate\Database\Schema\Builder
     */
    private $schema;

    /**
     * CreateCouponsTable constructor.
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
            /** @var Illuminate\Database\Connection $db */
            $db = \Illuminate\Support\Facades\DB::getFacadeRoot();
            $this->schema->create(self::$tableName, function (Blueprint $table) use ($db) {
                $table->uuid('id');
                $table->uuid('issuer');
                $table->longText('session')->nullable();
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
        if ($this->schema->hasTable(self::$tableName))
        {
//            $this->schema->table(self::$tableName, function (Blueprint $table) {
//                $table->dropForeign('sessions_issuer_foreign');
//            });
        }
        else
        {
            echo 'Table Not Exists';
        }

        $this->schema->dropIfExists(self::$tableName);
    }
}

