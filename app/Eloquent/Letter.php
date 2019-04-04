<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 01 April 2019, 9:00 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    const table = 'letters';
    const letterKind = [
        'incoming',
        'outgoing'
    ];

    /**
     * @var bool
     */
    public $timestamps = true;
    /**
     * @var string
     */
    protected $table = Letter::table;
    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'date'
    ];
    /**
     * @var array
     */
    protected $guarded = [
        'id',
        'issuer',
        'number',
        'created_at',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'code',
        'index',
        'subject',
        'date',
        'kind',
        'file',
        'updated_at'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
        'issuer' => 'string'
    ];
    public $incrementing = false;

    /**
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->{'id'};
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}

?>
