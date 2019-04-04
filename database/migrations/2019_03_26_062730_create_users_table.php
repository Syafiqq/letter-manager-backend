<?php

use App\Eloquent\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * @var string
     */
    static $tableName = User::table;
    /**
     * @var Illuminate\Database\Schema\Builder
     */
    private $schema;

    /**
     * CreateCounselorAccount constructor.
     */
    public function __construct()
    {
        $this->schema = Schema::getFacadeRoot();
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
                $table->string('credential', 100)->unique();
                $table->string('email', 100)->nullable();
                $table->string('name', 100);
                $table->enum('gender', User::genders);
                $table->enum('role', User::roles);
                $table->uuid('stamp');
                $table->string('avatar', 100)->nullable();
                $table->string('password', 60);
                $table->uuid('lost_password')->nullable();
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
