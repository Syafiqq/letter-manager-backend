<?php

namespace App\Eloquent;

use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    const table = 'users';
    const roles = ['student'];
    const genders = ['male', 'female'];
    /**
     * @var bool
     */
    public $timestamps = true;
    /**
     * @var string
     */
    protected $table = User::table;
    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    /**
     * @var array
     */
    protected $guarded = [
        'id',
        'credential',
        'role',
        'stamp',
        'password',
        'lost_password',
        'created_at',
        'updated_at'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'avatar'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'lost_password'
    ];
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string'
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
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->{'password'};
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function session()
    {
        return $this->hasMany('\App\Eloquent\Session', 'issuer', 'id');
    }

    /**
     * @return User
     * @throws Exception
     */
    public function generateRecoveryCode()
    {

        $this->{'lost_password'} = Uuid::uuid4()->toString();

        return $this;
    }
}
