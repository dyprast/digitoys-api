<?php

namespace App\Http\Controllers\SubDistributor;

use App\Region;

use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
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
     * Get region data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function get(Request $request)
    {
        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $region       = new Region();
        $region       = $region->orderBy($sortColumn, $order)->paginate($limit);

        $code           = 200;
        $message        = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $region->currentPage(),
                    'perPage'       => $region->perPage(),
                    'total'         => $region->total(),
                    'data'          => $region->items(),
                ]
            ],
            $code
        );
    }
    
}