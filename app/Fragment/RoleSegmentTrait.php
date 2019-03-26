<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 27 March 2019, 2:34 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App;


use Illuminate\Http\Request;

trait RoleSegmentTrait
{
    /**
     * @param Request $request
     * @return string
     */
    private function getRole(Request $request)
    {
        return $request->segment(1, null);
    }
}

?>
