<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * @var string
     */
    static $tableName = \App\Eloquents\User::table;
    /**
     * @var Illuminate\Database\Schema\Builder
     */
    private $schema;

    /**
     * CreateCounselorAccount constructor.
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
                $table->string('credential', 100)->unique();
                $table->string('email', 100)->nullable();
                $table->string('name', 100);
                $table->enum('gender', \App\Eloquents\User::genders);
                $table->enum('role', \App\Eloquents\User::roles);
                $table->uuid('stamp');
                $table->string('avatar', 100)->nullable();
                $table->string('password', 60);
                $table->rememberToken();
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
                $table->primary('id');
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
