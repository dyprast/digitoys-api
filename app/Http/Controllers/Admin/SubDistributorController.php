<?php

namespace App\Http\Controllers\Admin;

use App\SubDistributor;
use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
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
     * Get data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function get(Request $request)
    {
        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $subDistributor        = new SubDistributor();

        if ($request->search) {
            $search             = $request->search;
            $subDistributor    = $subDistributor->where(function ($subDistributor) use ($search) {
                return $subDistributor
                    ->where('name',             'LIKE',     '%' . $search . '%')
                    ->orWhere('username',       'LIKE',     '%' . $search . '%')
                    ->orWhere('email',          'LIKE',     '%' . $search . '%')
                    ->orWhere('password',       'LIKE',     '%' . $search . '%')
                    ->orWhere('phone_number',   'LIKE',     '%' . $search . '%');
            })
            ->orWhere(function ($subDistributor) use ($search) {
                return $subDistributor;
            });
        }

        $subDistributor    = $subDistributor->orderBy($sortColumn, $order)->paginate($limit);

        $code               = 200;
        $message            = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $subDistributor->currentPage(),
                    'perPage'       => $subDistributor->perPage(),
                    'total'         => $subDistributor->total(),
                    'data'          => $subDistributor->items(),
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
        $subDistributor   = SubDistributor::find($id);
        $code               = 200;
        $message            = 'Data successfully retrieved';

        if (!isset($subDistributor)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The sub distributor id ' . $id . ' not found';
            $report     = 'Cannot update sub distributor with id ' . $id . '. ' . $message;

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
                    'data'  => $subDistributor
                ]
            ],
            $code
        );
    }
    
    /**
     * Create data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function create(Request $request)
    {
        $auth           = $request->admin;

        $validator      = Validator::make(
            $request->all(),
            [
                'sub_region_id'     => 'required',
                'name'              => 'required',
                'username'          => 'required|unique:main_distributors,username,NULL,id,deleted_at,NULL',
                'password'          => 'required',
                'email'             => 'required|unique:main_distributors,email,NULL,id,deleted_at,NULL',
                'phone_number'      => 'required|numeric',
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

        $subDistributor                  = new SubDistributor();
        $subDistributor->sub_region_id   = $request->sub_region_id;
        $subDistributor->name            = $request->name;
        $subDistributor->password        = Hash::make($request->password);
        $subDistributor->username        = $request->username;
        $subDistributor->email           = $request->email;
        $subDistributor->phone_number    = $request->phone_number;
        $subDistributor->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully created';
        $report     = 'The sub distributor ' . $auth->name . ' has successfully created the sub distributor ' . $subDistributor->name . ' (id: ' . $subDistributor->id . ')';

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $subDistributor,
                ]
            ],
            $code
        );
    }

    /**
     * Create update data
     * 
     * @param \Illuminate\Http\Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $auth           = $request->admin;

        $subDistributor          = SubDistributor::find($id);

        $validator      = Validator::make(
            $request->all(),
            [
                'sub_region_id'     => 'required',
                'name'              => 'required',
                'username'          => 'unique:main_distributors,username,' . $id . ',id,deleted_at,NULL',
                'email'             => 'unique:main_distributors,email,' . $id . ',id,deleted_at,NULL',
                'phone_number'      => 'numeric',
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

        if (!isset($subDistributor)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The sub distributor id ' . $id . ' not found';
            $report     = 'The sub distributor ' . $auth->name . ' cannot update sub distributor with id ' . $id . '. ' . $message;

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

        $subDistributor->sub_region_id       = isset($request->sub_region_id)    ? $request->sub_region_id      : $subDistributor->sub_region_id;
        $subDistributor->name                = isset($request->name)             ? $request->name                        : $subDistributor->name;
        $subDistributor->username            = isset($request->username)         ? $request->username                : $subDistributor->username;
        $subDistributor->password            = isset($request->password)         ? Hash::make($request->password)    : $subDistributor->password;
        $subDistributor->email               = isset($request->email)            ? $request->email                   : $subDistributor->email;
        $subDistributor->phone_number        = isset($request->phone_number)     ? $request->phone_number        : $subDistributor->phone_number;
        $subDistributor->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully updated';
        $report     = 'The sub distributor ' . $auth->admin . ' has successfully updated sub distributor ' . $subDistributor->name . ' (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $subDistributor,
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

        $subDistributor          = SubDistributor::find($id);

        if (!isset($subDistributor)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The sub distributor id ' . $id . ' not found';
            $report     = 'The sub distributor ' . $auth->name . ' cannot delete sub distributor with id ' . $id . '. ' . $message;

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

        $subDistributor->delete();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully deleted';
        $report     = 'The sub distributor ' . $auth->admin . ' has successfully deleted sub distributor ' . $subDistributor->name . ' (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $subDistributor,
                ]
            ],
            $code
        );
    }
}
