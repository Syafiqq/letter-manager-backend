<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 27 March 2019, 2:57 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Eloquents;


use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    const table = 'sessions';
    /**
     * @var bool
     */
    public $timestamps = false;
    public $incrementing = false;
    /**
     * @var string
     */
    protected $table = Session::table;
    /**
     * @var array
     */
    protected $dates = [
    ];
    /**
     * @var array
     */
    protected $guarded = [
        'id',
        'issuer'
    ];
    /**
     * @var array
     */
    protected $fillable = [
        'session'
    ];
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string'
    ];

    /**
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->{'id'};
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('\App\Eloquents\User', 'issuer', 'id');
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
