<?php

namespace App\Http\Controllers\Admin;

use App\News;

use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{

    protected $actionHistory;
    protected $defaultLimit;
    protected $defaultSortColumn;
    protected $defaultOrder;

    public function __construct()
    {
        $this->actionHistory        = new ActionHistoryRepository;
        $this->defaultLimit         = 10;
        $this->defaultSortColumn    = 'datetime';
        $this->defaultOrder         = 'desc';
    }

    /**
     * Get news data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function get(Request $request)
    {
        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $news           = new News();
        $news           = $news->orderBy($sortColumn, $order)->paginate($limit);

        $code           = 200;
        $message        = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $news->currentPage(),
                    'perPage'       => $news->perPage(),
                    'total'         => $news->total(),
                    'data'          => $news->items(),
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
        $validator      = Validator::make(
            $request->all(),
            [
                'title'         => 'required',
                'text'          => 'required',
                'datetime'      => 'required',
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

        $news                        = new News();
        $news->title                 = $request->title;
        $news->text                  = $request->text;
        $news->datetime              = $request->datetime;
        $news->image_video           = isset($request->image_video) ? $request->image_video : '';
        $news->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully created';
        $report     = 'The news ' . $request->name . ' has successfully created the news ' . $news->name . ' (id: ' . $news->id . ')';

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $news,
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
        $news           = news::find($id);

        $validator      = Validator::make(
            $request->all(),
            [
                'title'         => 'required',
                'text'          => 'required',
                'datetime'      => 'required',
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

        if (!isset($news)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The news id ' . $id . ' not found';
            $report     = 'Cannot update news with id ' . $id . '. ' . $message;

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

        $news->title                 = isset($request->title) ? $request->title : '';
        $news->text                  = isset($request->text) ? $request->text : '';
        $news->datetime              = isset($request->datetime) ? $request->datetime : '';
        $news->image_video           = isset($request->image_video) ? $request->image_video : '';
        $news->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully updated';
        $report     = 'The news ' . $request->name . ' has successfully updated news ' . $news->name . ' (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $news,
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
        $news           = news::find($id);

        if (!isset($news)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The news id ' . $id . ' not found';
            $report     = 'Cannot delete news with id ' . $id . '. ' . $message;

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

        $news->delete();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully deleted';
        $report     = 'The news ' . $news->name . ' has successfully deleted news (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $news,
                ]
            ],
            $code
        );
    }
}
