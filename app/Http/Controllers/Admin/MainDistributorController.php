<?php

namespace App\Http\Controllers\Admin;

use App\MainDistributor;
use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
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
     * Get data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function get(Request $request)
    {
        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $mainDistributor        = new MainDistributor();

        if ($request->search) {
            $search             = $request->search;
            $mainDistributor    = $mainDistributor->where(function ($mainDistributor) use ($search) {
                return $mainDistributor
                    ->where('name',             'LIKE',     '%' . $search . '%')
                    ->orWhere('username',       'LIKE',     '%' . $search . '%')
                    ->orWhere('email',          'LIKE',     '%' . $search . '%')
                    ->orWhere('password',       'LIKE',     '%' . $search . '%')
                    ->orWhere('phone_number',   'LIKE',     '%' . $search . '%');
            })
            ->orWhere(function ($mainDistributor) use ($search) {
                return $mainDistributor;
            });
        }

        $mainDistributor    = $mainDistributor->orderBy($sortColumn, $order)->paginate($limit);

        $code               = 200;
        $message            = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $mainDistributor->currentPage(),
                    'perPage'       => $mainDistributor->perPage(),
                    'total'         => $mainDistributor->total(),
                    'data'          => $mainDistributor->items(),
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
        $mainDistributor   = MainDistributor::find($id);
        $code               = 200;
        $message            = 'Data successfully retrieved';

        if (!isset($mainDistributor)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The main distributor id ' . $id . ' not found';
            $report     = 'Cannot update main distributor with id ' . $id . '. ' . $message;

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
                    'data'  => $mainDistributor
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

        $mainDistributor                  = new MainDistributor();
        $mainDistributor->sub_region_id   = $request->sub_region_id;
        $mainDistributor->name            = $request->name;
        $mainDistributor->password        = Hash::make($request->password);
        $mainDistributor->username        = $request->username;
        $mainDistributor->email           = $request->email;
        $mainDistributor->phone_number    = $request->phone_number;
        $mainDistributor->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully created';
        $report     = 'The main distributor ' . $auth->name . ' has successfully created the main distributor ' . $mainDistributor->name . ' (id: ' . $mainDistributor->id . ')';

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $mainDistributor,
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

        $mainDistributor          = MainDistributor::find($id);

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

        if (!isset($mainDistributor)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The main distributor id ' . $id . ' not found';
            $report     = 'The main distributor ' . $auth->name . ' cannot update main distributor with id ' . $id . '. ' . $message;

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

        $mainDistributor->sub_region_id       = isset($request->sub_region_id)    ? $request->sub_region_id      : $mainDistributor->sub_region_id;
        $mainDistributor->name                = isset($request->name)             ? $request->name                        : $mainDistributor->name;
        $mainDistributor->username            = isset($request->username)         ? $request->username                : $mainDistributor->username;
        $mainDistributor->password            = isset($request->password)         ? Hash::make($request->password)    : $mainDistributor->password;
        $mainDistributor->email               = isset($request->email)            ? $request->email                   : $mainDistributor->email;
        $mainDistributor->phone_number        = isset($request->phone_number)     ? $request->phone_number        : $mainDistributor->phone_number;
        $mainDistributor->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully updated';
        $report     = 'The main distributor ' . $auth->admin . ' has successfully updated main distributor ' . $mainDistributor->name . ' (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $mainDistributor,
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

        $mainDistributor          = MainDistributor::find($id);

        if (!isset($mainDistributor)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The main distributor id ' . $id . ' not found';
            $report     = 'The main distributor ' . $auth->name . ' cannot delete main distributor with id ' . $id . '. ' . $message;

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

        $mainDistributor->delete();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully deleted';
        $report     = 'The main distributor ' . $auth->admin . ' has successfully deleted main distributor ' . $mainDistributor->name . ' (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $mainDistributor,
                ]
            ],
            $code
        );
    }
}
