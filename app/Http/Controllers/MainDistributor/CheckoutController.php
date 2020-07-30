<?php

namespace App\Http\Controllers\MainDistributor;

use App\Transaction;
use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
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

        $transaction           = new Transaction();
        $transaction           = $transaction->with(
            [
                'product',
                'sub_distributor.sub_region.region'
            ]
        );
        $transaction           = $transaction->where('sub_distributor_id', $subDistributor->id);
        $transaction           = $transaction->orderBy($sortColumn, $order)->paginate($limit);

        $code           = 200;
        $message        = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $transaction->currentPage(),
                    'perPage'       => $transaction->perPage(),
                    'total'         => $transaction->total(),
                    'data'          => $transaction->items(),
                ]
            ],
            $code
        );
    }
}
