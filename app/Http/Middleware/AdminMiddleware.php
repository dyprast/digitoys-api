<?php

namespace App\Http\Middleware;

use App\Admin;
use App\Repositories\ActionHistoryRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Closure;

class AdminMiddleware
{
    protected $actionHistory;

    public function __construct()
    {
        $this->actionHistory        = new ActionHistoryRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth   = $request->header('Authorization');

        if (!Str::startsWith($auth, 'Basic ')) {
            $code       = 406;
            $status     = 'error';
            $message    = 'Not acceptable header: Basic Auth is null';
        }

        $token      = Str::replaceFirst('Basic ', '', $auth);

        if (!isset($token)) {
            $code       = 401;
            $status     = 'error';
            $message    = 'Authentication failed: auth result empty';

            $this->actionHistory->create($message);

            return response()->json(
                [
                    'code'      => $code,
                    'status'    => $status,
                    'message'   => $message,
                ],
                $code
            );
        }

        $adminData  = explode(':', base64_decode($token));
        $username   = $adminData[0];
        $password   = $adminData[1];

        $admin      = Admin::where('username', $username)->first();

        if (!isset($admin)) {
            $code       = 404;
            $status     = 'status';
            $message    = 'Authentication failed: admin ' . $username . ' not found';

            $this->actionHistory->create($message);

            return response()->json(
                [
                    'code'      => $code,
                    'status'    => $status,
                    'message'   => $message,
                ],
                $code
            );
        }

        if (!Hash::check($password, $admin->password)) {
            $code       = 401;
            $status     = 'error';
            $message    = 'Authentication failed: password did not match for admin ' . $username;

            $this->actionHistory->create($message);

            return response()->json(
                [
                    'code'      => $code,
                    'status'    => $status,
                    'message'   => $message,
                ],
                $code
            );
        }

        $request->admin = $admin;

        return $next($request);
    }
}
