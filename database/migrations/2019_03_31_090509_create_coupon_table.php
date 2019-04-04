<?php

use App\Eloquent\Coupon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponTable extends Migration
{
    /**
     * @var string
     */
    static $tableName = Coupon::table;
    /**
     * @var Illuminate\Database\Schema\Builder
     */
    private $schema;

    /**
     * CreateCouponsTable constructor.
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
                $table->string('coupon', 20)->unique();
                $table->uuid('assignee');
                $table->enum('usage', Coupon::usages);
                $table->timestamp('created_at');
                $table->primary('id');
                $table->foreign('assignee')
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
