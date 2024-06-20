<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // 管理者としてログイン済みの状態でログインページにアクセスした場合は、管理者用のトップページにリダイレクトさせたい
                if ($guard === 'admin') {

                    // adminだとここ
                    Log::info("middleware: " . get_class() . ", Kernel.php上で:'guest'" . ", チェックしたいguardは:" . $guard . ", requestは:" . $request->url() . ", ifは:「if ($guard === 'admin')」");

                    return redirect(RouteServiceProvider::ADMIN_HOME);
                }

                // guardとは？ 一般ログインだとどうなる？ 完全ゲストだとどうなる？
                Log::info("middleware: " . get_class() . ", Kernel.php上で:'guest'" . ", チェックしたいguardは:guardは:" . $guard . ", requestは:" . $request->url() . ", ifは:「(Auth::guard($guard)->check())」");

                // $guestパラメータを web に指定して、webでログインすると、ここを通る
                return redirect(RouteServiceProvider::HOME);
            }
        }
        // 一般ログインだとここ
        // Log::debug("middleware: " . get_class() . ", Kernel.php上で 'guest'" . ", チェックしたいguardは:guardは:" . $guard . ", requestは:" . $request->url() . ", ifは なし". ", userのid:" . $request->user()?->id);

        return $next($request);
    }
}
