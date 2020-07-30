<?php

namespace App\Http\Controllers\MainDistributor;

use App\Product;

use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
     * Get product data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function get(Request $request)
    {
        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $product        = new Product();

        $product        = $product->with(
            [
                'product_images',
                'category',
                'product_label'
            ]
        );

        if ($request->id) {
            $product = $product->where('id', $request->id);
        }

        $code           = 200;
        $message        = 'Data successfully retrieved';

        if ($request->id) {
            $product        = $product->first();

            return response()->json(
                [
                    'code'      => $code,
                    'message'   => $message,
                    'results'   => [
                        'data'          => $product,
                    ]
                ],
                $code
            );
        } else {
            $product        = $product->orderBy($sortColumn, $order)->paginate($limit);

            return response()->json(
                [
                    'code'      => $code,
                    'message'   => $message,
                    'results'   => [
                        'currentPage'   => $product->currentPage(),
                        'perPage'       => $product->perPage(),
                        'total'         => $product->total(),
                        'data'          => $product->items(),
                    ]
                ],
                $code
            );
        }
    }
}
