<?php namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseAuth;
use Illuminate\Hashing\HashManager;
use Tymon\JWTAuth\Factory;

class Auth extends BaseAuth
{
    public function __construct(Factory $factory, HashManager $hashManager)
    {
        parent::__construct($factory, $hashManager);
        $this->role = 'student';
    }

}
