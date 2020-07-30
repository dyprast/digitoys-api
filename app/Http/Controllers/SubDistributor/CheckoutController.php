<?php

namespace App\Http\Controllers\SubDistributor;

use App\Product;
use App\Order;
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

    public function show(Request $request, $id)
    {

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
        $transaction           = $transaction->where("id", $id);
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

    public function create(Request $request)
    {
        $transaction = $request->sub_distributor;

        $validator      = Validator::make(
            $request->all(),
            [
                'sub_distributor_id'     => 'required|exists:sub_distributors,id,deleted_at,NULL',
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


        $product                    = Product::find($request->product_id);

        $invoice_number__           = '';
        $transaction                = Transaction::orderBy("id", "DESC")->first();
        if($transaction){
            $last_invoice_number    = substr($transaction->invoice_number, -6) + 1;
            $number_invoice         = sprintf('%06d', $last_invoice_number);
            $invoice_number__       = "INV".date("Ymd").$number_invoice;
        }
        else{
            $invoice_number__       = "INV".date("Ymd").sprintf('%06d', 1);
        }

        $sub_total__                = $product->price;
        $grand_total__              = $product->price*$request->quantity;

        $order_number__             = '';
        $order                      = Order::orderBy("id", "DESC")->first();
        if($order){
            $last_order_number      = $order->order_number;
            $new_order_number       = str_replace('#','',$last_order_number);
            $order_number__         = "#".sprintf('%06d', $new_order_number + 1);
        }
        else{
            $order_number__         = '#'.sprintf('%06d', 1);
        }

        $transaction                       = new Transaction;
        $transaction->sub_distributor_id   = $request->sub_distributor_id;
        $transaction->invoice_number       = $invoice_number__;
        $transaction->sub_total            = $sub_total__;                 // SUB TOTAL (CAPTURED PRICE)
        $transaction->quantity             = $request->quantity;
        $transaction->grand_total          = $grand_total__;               // GRAND TOTAL
        $transaction->status               = '';
        $transaction->refusal_reason       = $request->refusal_reason;
        $transaction->save();

        $order                              = new Order;
        $order->transaction_id              = $transaction->id;
        $order->sub_distributor_id          = $request->sub_distributor_id;
        $order->product_id                  = $request->product_id;
        $order->order_number                = $order_number__;
        $order->captured_price              = $sub_total__;                 // SUB TOTAL (CAPTURED PRICE)
        $order->sub_total                   = $sub_total__;                 // SUB TOTAL (CAPTURED PRICE)
        $order->quantity                    = $request->quantity;
        $order->grand_total                 = $grand_total__;               // GRAND TOTAL
        $order->grand_total                 = $grand_total__;               // GRAND TOTAL
        $order->status                      = '';
        $order->refusal_reason              = $request->refusal_reason;
        $order->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully created';
        $report     = 'The cart with ' . $request->product_id . ' has successfully created by ' . $transaction->name;

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $transaction,
                ]
            ],
            $code
        );
    }

    public function delete($id)
    {
        $transaction    = Transaction::find($id);

        if (!isset($transaction)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The cart with id ' . $id . ' not found';
            $report     = 'Cannot delete transaction with id ' . $id . '. ' . $message;

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

        $transaction->delete();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully deleted';
        $report     = 'The transaction ' . $transaction->id . ' has successfully deleted transaction (id: ' . $id . ') ';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $transaction,
                ]
            ],
            $code
        );
    }
}
