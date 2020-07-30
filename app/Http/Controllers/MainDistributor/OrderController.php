<?php

namespace App\Http\Controllers\MainDistributor;

use App\Order;
use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
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

    public function get(Request $request)
    {
        $mainDistributor = $request->main_distributor;

        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $sortOrder      = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $order           = new Order();
        $order           = $order->with(
            [
                'product',
                'sub_distributor.sub_region.region',
                'main_distributor.sub_region.region',
            ]
        );

        if (in_array($request->self, ['true', 1, true])) {
            $order      = $order->where('main_distributor_id', $mainDistributor->id);
        } else {
            $order      = $order->whereNull('main_distributor_id');
        }

        $code           = 200;
        $message        = 'Data successfully retrieved';

        if ($request->id) {
            $order      = $order->where('id', $request->id)->first();

            return response()->json(
                [
                    'code'      => $code,
                    'message'   => $message,
                    'results'   => [
                        'currentPage'   => $order->currentPage(),
                        'perPage'       => $order->perPage(),
                        'total'         => $order->total(),
                        'data'          => $order->items(),
                    ]
                ],
                $code
            );
        } else {
            $order      = $order->orderBy($sortColumn, $sortOrder)->paginate($limit);

            return response()->json(
                [
                    'code'      => $code,
                    'message'   => $message,
                    'results'   => [
                        'currentPage'   => $order->currentPage(),
                        'perPage'       => $order->perPage(),
                        'total'         => $order->total(),
                        'data'          => $order->items(),
                    ]
                ],
                $code
            );
        }
    }

    public function accept(Request $request, $id)
    {
        $subDistributor = $request->sub_distributor;

        $validator      = Validator::make(
            $request->all(),
            [
                'main_distributor_id'               => 'required',
            ]
        );

        $order                     = Order::find($id);

        if (!isset($order)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'Order not found';
            $report     = 'Cannot delete order with id ' . $order->id . '. ' . $message;

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

        $order->main_distributor_id  = $request->main_distributor_id;
        $order->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully updated';
        $report     = 'The order with ' . $request->product_id . ' has successfully update by ' . $subDistributor->name;

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $order,
                ]
            ],
            $code
        );
    }
}
