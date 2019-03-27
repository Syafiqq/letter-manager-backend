<?php namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseAuth;
use Illuminate\Contracts\Hashing\Hasher;
use Tymon\JWTAuth\Factory;
use Tymon\JWTAuth\JWTAuth;

class Auth extends BaseAuth
{
    /**
     * Auth constructor.
     * @param Factory $factory
     * @param Hasher $hashManager
     * @param JWTAuth $jwtAuth
     */
    public function __construct(Factory $factory, Hasher $hashManager, JWTAuth $jwtAuth)
    {
        parent::__construct($factory, $hashManager, $jwtAuth);
        $this->role = 'student';
    }

}
