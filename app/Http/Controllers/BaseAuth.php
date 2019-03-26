<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 27 March 2019, 3:20 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Controllers;


use App\Eloquents\Session;
use App\Eloquents\User;
use App\Model\Popo\PopoMapper;
use App\Model\Util\HttpStatus;
use Illuminate\Hashing\HashManager;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Factory;
use Tymon\JWTAuth\Token;

abstract class BaseAuth extends Controller
{
    protected $role;
    private $jwtFactory;
    private $hashManager;

    public function __construct(Factory $factory, HashManager $hashManager)
    {
        $this->jwtFactory  = $factory;
        $this->hashManager = $hashManager;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function postLogin(Request $request)
    {
        $response = response()->json(PopoMapper::alertResponse(HttpStatus::UNAUTHORIZED, 'Unknown User'), HttpStatus::UNAUTHORIZED);

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
     * @param Token $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(Token $token)
    {
        return response()->json(PopoMapper::alertResponse(HttpStatus::OK, 'Login Sukses', [
            'token' => $token->get(),
            'type' => 'bearer',
            'expires' => $this->jwtFactory->getTTL()
        ]), HttpStatus::OK);
    }
}

?>
