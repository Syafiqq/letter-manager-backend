<?php namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseAuth;
use Illuminate\Hashing\HashManager;
use Tymon\JWTAuth\Factory;
use Tymon\JWTAuth\JWTAuth;

class Auth extends BaseAuth
{
    public function __construct(Factory $factory, HashManager $hashManager, JWTAuth $jwtAuth)
    {
        parent::__construct($factory, $hashManager, $jwtAuth);
        $this->role = 'student';
    }

}
