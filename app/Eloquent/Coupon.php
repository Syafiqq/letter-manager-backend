<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 8:47 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Eloquent;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    const table = 'coupons';
    const usages = User::roles;
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = Coupon::table;
    /**
     * @var array
     */
    protected $dates = [
        'created_at'
    ];
    /**
     * @var array
     */
    protected $guarded = [
        'id',
        'usage',
        'created_at',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon'
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
        'assignee' => 'string'
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
        return $this->belongsTo('\App\Eloquent\User', 'assignee', 'id');
    }

    public function getHumanReadableUsage()
    {
        if (empty($this->{'usage'}))
        {
            return null;
        }
        else
        {
            switch ($this->{'usage'})
            {
                case 'student':
                    return 'Student';
            }
        }
    }
}

?>
