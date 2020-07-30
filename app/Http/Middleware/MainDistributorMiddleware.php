<?php

namespace App\Http\Middleware;

use App\MainDistributor;
use App\Repositories\ActionHistoryRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Closure;

class MainDistributorMiddleware
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

        $token                  = Str::replaceFirst('Basic ', '', $auth);

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

        $mainDistributorData    = explode(':', base64_decode($token));
        $username               = $mainDistributorData[0];
        $password               = $mainDistributorData[1];

        $mainDistributor        = MainDistributor::where('username', $username)->first();

        if (!isset($mainDistributor)) {
            $code       = 404;
            $status     = 'status';
            $message    = 'Authentication failed: main distributor ' . $username . ' not found';

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

        if (!Hash::check($password, $mainDistributor->password)) {
            $code       = 401;
            $status     = 'error';
            $message    = 'Authentication failed: password did not match for main distributor ' . $username;

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

        $request->main_distributor = $mainDistributor;

        return $next($request);
    }
}
