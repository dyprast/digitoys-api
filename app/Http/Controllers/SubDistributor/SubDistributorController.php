<?php

namespace App\Http\Controllers\SubDistributor;

use App\SubDistributor;

use App\Http\Controllers\Controller;
use App\Repositories\ActionHistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SubDistributorController extends Controller
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
     * Authenticate sub distributor
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username'  => 'required|exists:sub_distributors,username',
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

        $subDistributor = SubDistributor::where('username', $request->username)->first();

        if (isset($subDistributor)) {
            if (Hash::check($request->password, $subDistributor->password)) {
                $basic_auth = base64_encode($subDistributor->username . ':' . $request->password);

                $subDistributor->remember_token = Str::random(32);
                $subDistributor->save();

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
                            'data'          => $subDistributor,
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
            $message    = 'Authentication failed: sub distributor ' . $request->username . ' not found';
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
