<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 30 March 2019, 4:10 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

if (!function_exists('path_route'))
{
    /**
     * Generate a URL to a named route.
     *
     * @param  string $name
     * @param  array $parameters
     * @param  bool|null $secure
     * @return string
     */
    function path_route($name, $parameters = [], $secure = null)
    {
        $domain = env('APP_URL');
        if (is_bool($secure) && $secure == true)
        {
            $domain = str_replace('http', 'https', $domain);
        }
        $route = app('url')->route($name, $parameters, $secure);

        return str_replace($domain, '', $route);
    }
}

?>
