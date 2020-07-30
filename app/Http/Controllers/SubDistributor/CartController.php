<?php

namespace App\Http\Controllers\SubDistributor;

use App\Cart;
use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
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
        $subDistributor = $request->sub_distributor;

        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $cart           = new Cart();
        $cart           = $cart->with(
            [
                'product',
                'sub_distributor.sub_region.region'
            ]
        );
        $cart           = $cart->where('sub_distributor_id', $subDistributor->id);
        $cart           = $cart->orderBy($sortColumn, $order)->paginate($limit);

        $code           = 200;
        $message        = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $cart->currentPage(),
                    'perPage'       => $cart->perPage(),
                    'total'         => $cart->total(),
                    'data'          => $cart->items(),
                ]
            ],
            $code
        );
    }

    public function create(Request $request)
    {
        $subDistributor = $request->sub_distributor;

        $validator      = Validator::make(
            $request->all(),
            [
                'product_id'             => 'required|exists:products,id,deleted_at,NULL',
                'quantity'               => 'required',
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

        $cart = Cart::where('product_id', $request->product_id)
            ->where('sub_distributor_id', $request->sub_distributor_id)
            ->first();

        if (isset($cart)) {
            $cart->quantity             = $cart->quantity + $request->quantity;
            $cart->save();
        } else {
            $cart                       = new Cart;
            $cart->sub_distributor_id   = $subDistributor->id;
            $cart->product_id           = $request->product_id;
            $cart->quantity             = $request->quantity;
            $cart->save();
        }


        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully created';
        $report     = 'The cart with ' . $request->product_id . ' has successfully created by ' . $subDistributor->name;

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $cart,
                ]
            ],
            $code
        );
    }

    public function update(Request $request, $id)
    {
        $subDistributor = $request->sub_distributor;

        $cart                       = Cart::find($id);

        if (!isset($cart)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'Cart not found';
            $report     = 'Cannot delete cart with id ' . $cart->id . '. ' . $message;

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

        $cart->sub_distributor_id   = $subDistributor->id;
        $cart->product_id           = isset($request->product_id)   ? $request->product_id  :   $cart->product_id;
        $cart->quantity             = isset($request->quantity)     ? $request->quantity    :   $cart->quantity;
        $cart->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully updated';
        $report     = 'The cart with ' . $request->product_id . ' has successfully update by ' . $subDistributor->name;

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $cart,
                ]
            ],
            $code
        );
    }

    public function delete($id)
    {
        $cart           = Cart::find($id);

        if (!isset($cart)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The cart with id ' . $id . ' not found';
            $report     = 'Cannot delete sub region with id ' . $id . '. ' . $message;

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

        $cart->delete();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully deleted';
        $report     = 'The sub region ' . $cart->name . ' has successfully deleted sub region (id: ' . $id . ') ';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $cart,
                ]
            ],
            $code
        );
    }
}
