<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Repositories\ActionHistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
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
     * Authenticate admin
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username'  => 'required|exists:admins,username',
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

        $admin = Admin::where('username', $request->username)->first();

        if (isset($admin)) {
            if (Hash::check($request->password, $admin->password)) {
                $basic_auth = base64_encode($admin->username . ':' . $request->password);

                $admin->remember_token = Str::random(32);
                $admin->save();

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
                            'data'          => $admin,
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
            $message    = 'Authentication failed: admin ' . $request->username . ' not found';
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

    /**
     * Get admin data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function get(Request $request)
    {
        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $admin          = new Admin();

        if ($request->search) {
            $search     = $request->search;
            $admin      = $admin->where(function ($admin) use ($search) {
                return $admin
                    ->where('name',             'LIKE',     '%' . $search . '%')
                    ->orWhere('username',       'LIKE',     '%' . $search . '%')
                    ->orWhere('email',          'LIKE',     '%' . $search . '%')
                    ->orWhere('password',       'LIKE',     '%' . $search . '%')
                    ->orWhere('phone_number',   'LIKE',     '%' . $search . '%');
            });
        }

        $admin          = $admin->orderBy($sortColumn, $order)->paginate($limit);

        $code           = 200;
        $message        = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $admin->currentPage(),
                    'perPage'       => $admin->perPage(),
                    'total'         => $admin->total(),
                    'data'          => $admin->items(),
                ]
            ],
            $code
        );
    }

    /**
     * Show data
     * 
     * @param \Illuminate\Http\Request $request
     * @param $id
     */
    public function show(Request $request, $id)
    {
        $admin          = Admin::find($id);
        $code           = 200;
        $message        = 'Data successfully retrieved';

        if (!isset($admin)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The admin id ' . $id . ' not found';
            $report     = 'Cannot update admin with id ' . $id . '. ' . $message;

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

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'data'  => $admin
                ]
            ],
            $code
        );
    }

    /**
     * Create admin data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function create(Request $request)
    {
        $auth           = $request->admin;

        $validator      = Validator::make(
            $request->all(),
            [
                'name'          => 'required',
                'username'      => 'required|unique:admins,username,NULL,id,deleted_at,NULL',
                'password'      => 'required',
                'email'         => 'required|unique:admins,email,NULL,id,deleted_at,NULL',
                'phone_number'  => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            $code       = 422;
            $status     = 'error';
            $message    = $validator->errors()->first();

            return response()->json(
                [
                    'code'      => $code,
                    'status'    => $status,
                    'message'   => $message,
                ],
                $code
            );
        }

        $admin                  = new Admin();
        $admin->name            = $request->name;
        $admin->password        = Hash::make($request->password);
        $admin->username        = $request->username;
        $admin->email           = $request->email;
        $admin->phone_number    = $request->phone_number;
        $admin->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully created';
        $report     = 'The admin ' . $auth->name . ' has successfully created the admin ' . $admin->name . ' (id: ' . $admin->id . ')';

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $admin,
                ]
            ],
            $code
        );
    }

    /**
     * Update data
     * 
     * @param \Illuminate\Http\Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $auth           = $request->admin;

        $admin          = Admin::find($id);

        $validator      = Validator::make(
            $request->all(),
            [
                'name'          => 'required',
                'username'      => 'unique:admins,username,' . $id . ',id,deleted_at,NULL',
                'email'         => 'unique:admins,email,' . $id . ',id,deleted_at,NULL',
                'phone_number'  => 'numeric',
            ]
        );

        if ($validator->fails()) {
            $code       = 422;
            $status     = 'error';
            $message    = $validator->errors()->first();

            return response()->json(
                [
                    'code'      => $code,
                    'status'    => $status,
                    'message'   => $message,
                ],
                $code
            );
        }

        if (!isset($admin)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The admin id ' . $id . ' not found';
            $report     = 'The admin ' . $auth->name . ' cannot update admin with id ' . $id . '. ' . $message;

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

        $admin->name            = isset($request->name)         ? $request->name                    : $admin->name;
        $admin->username        = isset($request->username)     ? $request->username                : $admin->username;
        $admin->password        = isset($request->password)     ? Hash::make($request->password)    : $admin->password;
        $admin->email           = isset($request->email)        ? $request->email                   : $admin->email;
        $admin->phone_number    = isset($request->phone_number) ? $request->phone_number            : $admin->phone_number;
        $admin->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully updated';
        $report     = 'The admin ' . $auth->admin . ' has successfully updated admin ' . $admin->name . ' (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $admin,
                ]
            ],
            $code
        );
    }

    /**
     * Delete data
     * 
     * @param \Illuminate\Http\Request $request
     * @param $id
     */
    public function delete(Request $request, $id)
    {
        $auth           = $request->admin;

        $admin          = Admin::find($id);

        if (!isset($admin)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The admin id ' . $id . ' not found';
            $report     = 'The admin ' . $auth->name . ' cannot delete admin with id ' . $id . '. ' . $message;

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

        $admin->delete();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully deleted';
        $report     = 'The admin ' . $auth->admin . ' has successfully deleted admin ' . $admin->name . ' (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $admin,
                ]
            ],
            $code
        );
    }
}
