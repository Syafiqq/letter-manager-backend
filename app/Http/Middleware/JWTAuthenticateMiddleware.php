<?php
/**
 * This <konseling-003-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 16 November 2018, 6:23 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Middleware;

use App\Model\Popo\PopoMapper;
use App\Model\Util\ClaimTable;
use App\Model\Util\HttpStatus;
use App\Model\Util\Session;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Payload;

class JWTAuthenticateMiddleware extends \Tymon\JWTAuth\Http\Middleware\Authenticate
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        parent::__construct($auth);
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param  string $role
     *
     * @return mixed
     * @throws UnauthorizedHttpException
     *
     */
    public function handle($request, Closure $next, $role = null)
    {
        $this->authenticate($request);
        /** @var Payload $payload */
        $payload = $this->auth->getPayload();
        if (!is_null($role) && strtolower($payload->get(ClaimTable::ROLE)) != strtolower($role))
        {
            return response()->json(PopoMapper::alertResponse(HttpStatus::NOT_FOUND, 'You dont have Authorization to handle this request')->serialize(), HttpStatus::UNAUTHORIZED);
        }
        try
        {
            $sess             = \App\Eloquent\Session::where('id', $payload->get(ClaimTable::SESSION))->first();
            Session::$session = json_decode($sess == null ? '{}' : $sess->{'storage'} ?? '{}', true) ?? [];
        }
        catch (Exception $_)
        {
            Session::$session = [];
        }

        return $next($request);
    }
}

?>
