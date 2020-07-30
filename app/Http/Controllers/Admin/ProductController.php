<?php

namespace App\Http\Controllers\Admin;

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
                'product_label',
            ]
        );

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

    /**
     * Create data
     * 
     * @param \Illuminate\Http\Request $request
     */

    public function create(Request $request)
    {
        $validator      = Validator::make(
            $request->all(),
            [
                'admin_id'               => 'required',
                'category_id'            => 'required',
                'name'                   => 'required',
                'price'                  => 'required|numeric',
                'stock'                  => 'required|numeric',
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

        $product                        = new Product();
        $product->admin_id              = $request->admin_id;
        $product->category_id           = $request->category_id;
        $product->name                  = $request->name;
        $product->price                 = $request->price;
        $product->stock                 = $request->stock;
        $product->label                 = isset($request->label) ? $request->label : '';
        $product->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully created';
        $report     = 'The product ' . $request->name . ' has successfully created the product ' . $product->name . ' (id: ' . $product->id . ')';

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $product,
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
        $product          = Product::find($id);

        $validator        = Validator::make(
            $request->all(),
            [
                'admin_id'               => 'required',
                'category_id'            => 'required',
                'name'                   => 'required',
                'price'                  => 'required|numeric',
                'stock'                  => 'required|numeric',
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

        if (!isset($product)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The product id ' . $id . ' not found';
            $report     = 'Cannot update product with id ' . $id . '. ' . $message;

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

        $product->admin_id              = $request->admin_id;
        $product->category_id           = $request->category_id;
        $product->name                  = $request->name;
        $product->price                 = $request->price;
        $product->stock                 = $request->stock;
        $product->label                 = isset($request->label) ? $request->label : '';
        $product->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully updated';
        $report     = 'The product ' . $request->name . ' has successfully updated product ' . $product->name . ' (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $product,
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
        $product          = Product::find($id);

        if (!isset($product)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The product id ' . $id . ' not found';
            $report     = 'Cannot delete product with id ' . $id . '. ' . $message;

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

        $product->delete();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully deleted';
        $report     = 'The product ' . $product->name . ' has successfully deleted product (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $product,
                ]
            ],
            $code
        );
    }
}
