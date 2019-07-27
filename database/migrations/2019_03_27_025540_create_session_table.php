<?php /** @noinspection PhpUndefinedMethodInspection */

use App\Eloquent\Session;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTable extends Migration
{
    /**
     * @var string
     */
    static $tableName = Session::table;
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
        $this->schema->dropIfExists(self::$tableName);
    }
}

