<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
     * Get category data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function get(Request $request)
    {
        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $category       = new Category();
        $category       = $category->orderBy($sortColumn, $order)->paginate($limit);

        $code           = 200;
        $message        = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $category->currentPage(),
                    'perPage'       => $category->perPage(),
                    'total'         => $category->total(),
                    'data'          => $category->items(),
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
                'name'                   => 'required',
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

        $category                        = new Category();
        $category->parent_category_id    = isset($request->parent_category_id) ? $request->parent_category_id : '';
        $category->name                  = $request->name;
        $category->description           = isset($request->description) ? $request->description : '';
        $category->icon                  = isset($request->icon) ? $request->icon : '';
        $category->banner                = isset($request->banner) ? $request->banner : '';
        $category->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully created';
        $report     = 'The category ' . $request->name . ' has successfully created the category ' . $category->name . ' (id: ' . $category->id . ')';

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $category,
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
        $category          = Category::find($id);

        $validator      = Validator::make(
            $request->all(),
            [
                'name'                   => 'required',
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

        if (!isset($category)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The category id ' . $id . ' not found';
            $report     = 'Cannot update category with id ' . $id . '. ' . $message;

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

        $category->parent_category_id    = isset($request->parent_category_id) ? $request->parent_category_id : $category->parent_category_id;
        $category->name                  = isset($request->name) ? $request->name : $category->name;
        $category->description           = isset($request->description) ? $request->description : $category->description;
        $category->description           = isset($request->description) ? $request->description : $category->description;
        $category->icon                  = isset($request->icon) ? $request->icon : $category->icon;
        $category->banner                = isset($request->banner) ? $request->banner : $category->banner;
        $category->save();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully updated';
        $report     = 'The category ' . $request->name . ' has successfully updated category ' . $category->name . ' (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $category,
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
        $category          = Category::find($id);

        if (!isset($category)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The category id ' . $id . ' not found';
            $report     = 'Cannot delete category with id ' . $id . '. ' . $message;

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

        $category->delete();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully deleted';
        $report     = 'The category ' . $category->name . ' has successfully deleted category (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $category,
                ]
            ],
            $code
        );
    }
}
