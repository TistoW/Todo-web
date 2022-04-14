<?php

namespace App\Http\Middleware;

use App\Http\Helper;
use App\Http\Response;
use App\Models\PersonalToken;
use Closure;
use Illuminate\Http\Request;

class AuthUser {
    use Helper;
    use Response;
    public function handle(Request $request, Closure $next) {
        $token = $request->header('token');
        if ($token) {
            $user = PersonalToken::where("token", $token)->first();
            if ($user) {
                return $next($request);
            }
        }
        return $this->error("Not Authorized", "NOT_AUTHORIZED", 401);
    }
}
