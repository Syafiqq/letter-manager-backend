<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 27 March 2019, 2:35 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Middleware;


use App\Eloquent\User;
use App\Model\Popo\PopoMapper;
use App\Model\Util\HttpStatus;
use App\RoleSegmentTrait;
use Closure;
use Illuminate\Http\Request;

class RegisteredRoleMiddleware
{
    use RoleSegmentTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role = $this->getRole($request);
        $role = strtolower($role);
        if (empty($role) || !in_array($role, User::roles))
        {
            return response()
                ->json(PopoMapper::alertResponse(HttpStatus::NOT_FOUND, 'Page Not Found')
                    ->serialize(), HttpStatus::NOT_FOUND);
        }
        $request->request->set('role', $role);

        return $next($request);
    }
}

?>
