<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 27 March 2019, 3:20 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Controllers;


use App\Eloquents\Coupon;
use App\Eloquents\Session;
use App\Eloquents\User;
use App\Model\Popo\PopoMapper;
use App\Model\Util\ClaimTable;
use App\Model\Util\HttpStatus;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Factory;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Token;

abstract class BaseAuth extends Controller
{
    protected $role;
    private $jwtFactory;
    private $jwtAuth;
    private $hashManager;

    /**
     * Auth constructor.
     * @param Factory $factory
     * @param Hasher $hashManager
     * @param JWTAuth $jwtAuth
     */
    public function __construct(Factory $factory, Hasher $hashManager, JWTAuth $jwtAuth)
    {
        $this->jwtFactory  = $factory;
        $this->hashManager = $hashManager;
        $this->jwtAuth     = $jwtAuth;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function postLogin(Request $request)
    {
        $response = response()->json(PopoMapper::alertResponse(HttpStatus::NOT_FOUND, 'Unknown User'), HttpStatus::UNAUTHORIZED);

        $credentials = $this->validate($request, [
            'credential' => 'bail|required|max:100',
            'password' => 'bail|required|min:8',
            'role' => "bail|required|in:{$this->role}",
        ]);

        // Find the user by email
        /** @var User $user */
        $user = User::where('credential', $credentials['credential'])->where('role', $credentials['role'])->first();
        if (!$user)
        {
            return response()->json(PopoMapper::alertResponse(HttpStatus::BAD_REQUEST, 'Cannot find user with provided credential'), HttpStatus::BAD_REQUEST);
        }
        if (!$this->hashManager->check($credentials['password'], $user->{'password'}))
        {
            return $response;
        }
        $session              = new Session();
        $session->{'id'}      = Uuid::uuid1()->toString();
        $session->{'session'} = json_encode([]);
        $user->session()->save($session);

        return $this->respondWithToken($this->jwt($user, $session));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function postRegister(Request $request)
    {
        $credentials = $this->validate($request, [
            'credential' => 'bail|required|max:100|unique:users',
            'email' => 'bail|required|max:100|email',
            'name' => 'bail|required|max:100',
            'gender' => 'bail|required|in:male,female',
            'role' => 'bail|required|in:student',
            'password' => 'bail|required|confirmed|min:8',
            'password_confirmation' => 'bail|required|min:8',
            'token' => "bail|required|exists:coupons,coupon,usage,{$this->role}",
        ]);

        /** @var Builder $coupon */
        $coupon = new Coupon();
        $coupon->where('coupon', $credentials['token'])->delete();

        $user                 = new User();
        $user->{'id'}         = Uuid::uuid1()->toString();
        $user->{'stamp'}      = Uuid::uuid4()->toString();
        $user->{'credential'} = $credentials['credential'];
        $user->{'email'}      = $credentials['email'];
        $user->{'name'}       = $credentials['name'];
        $user->{'gender'}     = $credentials['gender'];
        $user->{'role'}       = $credentials['role'];
        $user->{'avatar'}     = null;
        $user->{'password'}   = $this->hashManager->make($credentials['password'], []);
        $user->save();

        return response()->json(PopoMapper::alertResponse(HttpStatus::OK, 'User register successfully'), HttpStatus::OK);
    }

    /**
     * @param Token $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(Token $token)
    {
        return response()->json(PopoMapper::alertResponse(HttpStatus::OK, 'Login Success', [
            'token' => $token->get(),
            'type' => 'bearer',
            'expires' => $this->jwtFactory->getTTL()
        ]), HttpStatus::OK);
    }

    /**
     * @param User $user
     * @param Session $session
     * @return mixed
     */
    protected function jwt(User $user, Session $session)
    {
        $claims = [
            ClaimTable::AUDIENCE => 'k3f', //Audience of the token
            ClaimTable::ISSUER => url("/{$this->role}/auth/login"), // Issuer of the token
            ClaimTable::SUBJECT => $user->{'id'}, // Subject of the token
            ClaimTable::ISSUED_AT => time(), // Time when JWT was issued.
            ClaimTable::EXPIRATION => time() + 60 * 60, // Expiration time
            ClaimTable::AUTH_STAMP => $user->{'stamp'},
            ClaimTable::SESSION => $session->{'id'},
            ClaimTable::ROLE => $user->{'role'},
        ];

        $payload = $this->jwtFactory->customClaims($claims)->make();

        return $this->jwtAuth->manager()->encode($payload);
    }
}

?>
