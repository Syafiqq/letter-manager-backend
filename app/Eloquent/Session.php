<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 27 March 2019, 2:57 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Eloquent;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    const table = 'sessions';
    /**
     * @var bool
     */
    public $timestamps = false;
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'session'
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

    /**
     * @return BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('\App\Eloquent\User', 'issuer', 'id');
    }
}

?>
