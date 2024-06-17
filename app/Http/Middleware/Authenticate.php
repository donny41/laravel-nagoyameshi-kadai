<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // HTTPリクエストを受け取ったURLが'admin/*'の場合は、管理者用のログインページにリダイレクトする
        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        return $request->expectsJson() ? null : route('login');
    }
}
