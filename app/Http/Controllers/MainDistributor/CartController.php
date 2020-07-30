<?php

namespace App\Http\Controllers\MainDistributor;

use App\Cart;
use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
