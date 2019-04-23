<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 26 March 2019, 9:38 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Middleware;


use App\Model\Popo\PopoMapper;
use App\Model\Util\HttpStatus;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\Check;
use Tymon\JWTAuth\JWTAuth;

class GuestMiddleware extends Check
{
    /**
     * RedirectIfAuthenticated constructor.
     * @param JWTAuth $auth
     */
    public function __construct(JWTAuth $auth)
    {
        parent::__construct($auth);
    }


    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->parser()->setRequest($request)->hasToken())
        {
            try
            {
                if ($this->auth->parseToken()->authenticate() != null)
                {
                    return response()->json(PopoMapper::alertResponse(HttpStatus::FORBIDDEN, 'Can\'t access this page')->withAlertLevel('danger')->serialize(), HttpStatus::FORBIDDEN);
                }
            }
            catch (Exception $_)
            {
                //
            }
        }

        return $next($request);
    }
}

?>
