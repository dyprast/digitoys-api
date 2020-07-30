<?php

namespace App\Http\Controllers\SubDistributor;

use App\SubRegion;

use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubRegionController extends Controller
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
     * Get sub region data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function get(Request $request)
    {
        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $subRegion       = new SubRegion();
        $subRegion       = $subRegion->orderBy($sortColumn, $order)->paginate($limit);

        $code           = 200;
        $message        = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $subRegion->currentPage(),
                    'perPage'       => $subRegion->perPage(),
                    'total'         => $subRegion->total(),
                    'data'          => $subRegion->items(),
                ]
            ],
            $code
        );
    }
    
}
