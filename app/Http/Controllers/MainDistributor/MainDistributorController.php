<?php

namespace App\Http\Controllers\MainDistributor;

use App\MainDistributor;

use App\Http\Controllers\Controller;
use App\Repositories\ActionHistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MainDistributorController extends Controller
{
    protected $actionHistory;
    protected $defaultLimit;
    protected $defaultSortColumn;
    protected $defaultOrder;

    public function __construct()
    {
        $this->actionHistory        = new ActionHistoryRepository;
        $this->defaultLimit         = 10;
        $this->defaultSortColumn    = 'id';
        $this->defaultOrder         = 'desc';
    }

    /**
     * Authenticate main distributor
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username'  => 'required|exists:main_distributors,username',
                'password'  => 'required'
            ]
        );

        if ($validator->fails()) {
            $code       = 422;
            $status     = 'error';
            $message    = 'Authentication failed: ' . $validator->errors()->first();

            return response()->json(
                [
                    'code'      => $code,
                    'status'    => $status,
                    'message'   => $message,
                ],
                $code
            );
        }

        $mainDistributor = MainDistributor::where('username', $request->username)->first();

        if (isset($mainDistributor)) {
            if (Hash::check($request->password, $mainDistributor->password)) {
                $basic_auth = base64_encode($mainDistributor->username . ':' . $request->password);

                $mainDistributor->remember_token = Str::random(32);
                $mainDistributor->save();

                $code       = 200;
                $status     = 'success';
                $message    = 'Authentication verified to: ' . $request->username;
                $report     = $message;

                $this->actionHistory->create($report);

                return response()->json(
                    [
                        'code'      => $code,
                        'status'    => $status,
                        'message'   => $message,
                        'results'   => [
                            'data'          => $mainDistributor,
                            'basic_auth'    => $basic_auth,
                        ]
                    ],
                    $code
                );
            } else {
                $code       = 401;
                $status     = 'error';
                $message    = 'Authentication failed: password did not match for ' . $request->username;
                $report     = $message;

                $this->actionHistory->create($report);

                return response()->json(
                    [
                        'code'      => $code,
                        'status'    => $status,
                        'message'   => $message,
                    ],
                    $code
                );
            }
        } else {
            $code       = 404;
            $status     = 'error';
            $message    = 'Authentication failed: main distributor ' . $request->username . ' not found';
            $report     = $message;

            $this->actionHistory->create($report);

            return response()->json(
                [
                    'code'      => $code,
                    'status'    => $status,
                    'message'   => $message,
                ],
                $code
            );
        }
    }
}
