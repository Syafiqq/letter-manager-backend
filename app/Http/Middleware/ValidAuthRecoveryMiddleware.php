<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 31 March 2019, 6:05 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Middleware;


use App\Eloquent\User;
use App\Model\Popo\PopoMapper;
use App\Model\Util\HttpStatus;
use Closure;
use Illuminate\Http\Request;

class ValidAuthRecoveryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('token', '@)(I$)!@$');
        $user  = User::where('lost_password', '=', $token)->first();
        if (is_null($user))
        {
            return response()->json(PopoMapper::alertResponse(HttpStatus::NOT_FOUND, 'Page Not Found'), HttpStatus::NOT_FOUND);
        }

        return $next($request);
    }
}

?>
