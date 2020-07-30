<?php

namespace App\Http\Middleware;

use App\SubDistributor;
use App\Repositories\ActionHistoryRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Closure;

use function PHPSTORM_META\map;

class SubDistributorMiddleware
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

        $subDistributorData     = explode(':', base64_decode($token));
        $username               = $subDistributorData[0];
        $password               = $subDistributorData[1];

        $subDistributor         = SubDistributor::where('username', $username)->where('status', 'active')->first();

        if (!isset($subDistributor)) {
            $code       = 404;
            $status     = 'status';
            $message    = 'Authentication failed: subdistributor ' . $username . ' not found';

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

        if (!Hash::check($password, $subDistributor->password)) {
            $code       = 401;
            $status     = 'error';
            $message    = 'Authentication failed: password did not match for subdistributor ' . $username;

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

        $request->sub_distributor = $subDistributor;

        return $next($request);
    }
}
