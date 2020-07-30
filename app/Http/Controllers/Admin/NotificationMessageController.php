<?php

namespace App\Http\Controllers\Admin;

use App\NotificationMessage;
use App\NotificationMessageSend;

use App\Repositories\ActionHistoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationMessageController extends Controller
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
     * Get notification message data
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function get(Request $request)
    {
        $limit          = isset($request->limit)        ? $request->limit       : $this->defaultLimit;
        $sortColumn     = isset($request->sortColumn)   ? $request->sortColumn  : $this->defaultSortColumn;
        $order          = isset($request->order)        ? $request->order       : $this->defaultOrder;

        $notificationMessage           = new NotificationMessage();
        $notificationMessage           = $notificationMessage->with('NotificationMessageSend')->orderBy($sortColumn, $order)->paginate($limit);

        $code           = 200;
        $message        = 'Data successfully retrieved';

        return response()->json(
            [
                'code'      => $code,
                'message'   => $message,
                'results'   => [
                    'currentPage'   => $notificationMessage->currentPage(),
                    'perPage'       => $notificationMessage->perPage(),
                    'total'         => $notificationMessage->total(),
                    'data'          => $notificationMessage->items()
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
                'message'                       => 'required',
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

        $notificationMessage                        = new NotificationMessage();
        $notificationMessage->message               = $request->message;
        $notificationMessage->save();
        
        $sendToMainDistributors = $request->send_to_main_distributor;
        if(isset($sendToMainDistributors)){
            foreach($sendToMainDistributors as $sendToMainDistributor){
                $notificationMessageSendToMainDistributor = new NotificationMessageSend();
                $notificationMessageSendToMainDistributor->notification_message_id  = $notificationMessage->id;
                $notificationMessageSendToMainDistributor->main_distributor_id  = $sendToMainDistributor;
                $notificationMessageSendToMainDistributor->status  = "Sent";

                $notificationMessageSendToMainDistributor->save();

                $notificationMessageSendToMainDistributor_[] = $notificationMessageSendToMainDistributor;
            }
        }

        $sendToSubDistributors = $request->send_to_sub_distributor;
        if(isset($sendToSubDistributors)){
            foreach($sendToSubDistributors as $sendToSubDistributor){
                $notificationMessageSendToSubDistributor = new NotificationMessageSend();
                $notificationMessageSendToSubDistributor->notification_message_id  = $notificationMessage->id;
                $notificationMessageSendToSubDistributor->sub_distributor_id  = $sendToSubDistributor;
                $notificationMessageSendToSubDistributor->status  = "Sent";

                $notificationMessageSendToSubDistributor->save();

                $notificationMessageSendToSubDistributor_[] = $notificationMessageSendToSubDistributor;
            }
        }

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully created';
        $report     = 'The notification message ' . $request->message . ' has successfully created the notification message (id: ' . $notificationMessage->id . ')';

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'          => $notificationMessage,
                    'send_to_main_distributor'  => $notificationMessageSendToMainDistributor_,
                    'send_to_sub_distributor'   => $notificationMessageSendToSubDistributor_
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
        $notificationMessage           = NotificationMessage::find($id);

        $validator      = Validator::make(
            $request->all(),
            [
                'message'         => 'required',
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

        if (!isset($notificationMessage)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The notification message id ' . $id . ' not found';
            $report     = 'Cannot update notification message with id ' . $id . '. ' . $message;

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

        $notificationMessage->message             = isset($request->message) ? $request->message : '';
        $notificationMessage->save();

        NotificationMessageSend::where('notification_message_id',$id)->delete();

        $sendToMainDistributors = $request->send_to_main_distributor;
        if(isset($sendToMainDistributors)){
            foreach($sendToMainDistributors as $sendToMainDistributor){
                $notificationMessageSendToMainDistributor = new NotificationMessageSend();
                $notificationMessageSendToMainDistributor->notification_message_id  = $notificationMessage->id;
                $notificationMessageSendToMainDistributor->main_distributor_id  = $sendToMainDistributor;
                $notificationMessageSendToMainDistributor->status  = "Sent";

                $notificationMessageSendToMainDistributor->save();

                $notificationMessageSendToMainDistributor_[] = $notificationMessageSendToMainDistributor;
            }
        }

        $sendToSubDistributors = $request->send_to_sub_distributor;
        if(isset($sendToSubDistributors)){
            foreach($sendToSubDistributors as $sendToSubDistributor){
                $notificationMessageSendToSubDistributor = new NotificationMessageSend();
                $notificationMessageSendToSubDistributor->notification_message_id  = $notificationMessage->id;
                $notificationMessageSendToSubDistributor->sub_distributor_id  = $sendToSubDistributor;
                $notificationMessageSendToSubDistributor->status  = "Sent";

                $notificationMessageSendToSubDistributor->save();

                $notificationMessageSendToSubDistributor_[] = $notificationMessageSendToSubDistributor;
            }
        }

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully updated';
        $report     = 'The notification message ' . $request->message . ' has successfully updated notification message (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'                      => $notificationMessage,
                    'send_to_main_distributor'  => $notificationMessageSendToMainDistributor_,
                    'send_to_sub_distributor'   => $notificationMessageSendToSubDistributor_
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
        $notificationMessage           = NotificationMessage::find($id);

        if (!isset($notificationMessage)) {
            $code       = 404;
            $status     = 'error';
            $message    = 'The notification message id ' . $id . ' not found';
            $report     = 'Cannot delete notification message with id ' . $id . '. ' . $message;

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

        $notificationMessage->delete();

        $code       = 200;
        $status     = 'success';
        $message    = 'Data successfully deleted';
        $report     = 'The notification message ' . $notificationMessage->message . ' has successfully deleted notification message (id: ' . $id . ')';

        $this->actionHistory->create($report);

        return response()->json(
            [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
                'results'   => [
                    'data'  => $notificationMessage,
                ]
            ],
            $code
        );
    }
}
