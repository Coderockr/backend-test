<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Resources\EventInvitationCollection;
use App\Models\EventInvitation;

class EventInvitationController extends ApiController
{
    /**
     * @var EventInvitation
     */
    private $invitationModel;

    /**
     * EventInvitationController constructor.
     *
     * @param EventInvitation $invitationModel
     */
    public function __construct(EventInvitation $invitationModel)
    {
        $this->invitationModel = $invitationModel;
    }

    /**
     * Display a list of invitations to events of the user that are pending or rejected, which have not happened yet
     *
     * @return EventInvitationCollection
     */
    public function pending()
    {
        $user = auth('api')->user();

        $collection = $this->invitationModel->select($this->invitationModel->table . '.*')
                                            ->join('events', 'events.id', '=', 'event_id')
                                            ->ofGuest($user->id)
                                            ->pendingOrRejected()
                                            ->where('date', '>=', date('Y-m-d'))
                                            ->latest()
                                            ->get();

        return new EventInvitationCollection($collection);
    }

    /**
     * Call to the update status method to confirm an invitation
     *
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm($event_id)
    {
        return $this->updateStatus($event_id, 'confirm');
    }

    /**
     * Call to the update status method to reject an invitation
     *
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject($event_id)
    {
        return $this->updateStatus($event_id, 'rejected');
    }

    /**
     * Handles with confirm or reject the user friendship invitations
     *
     * @param $event_id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($event_id, $status)
    {
        try {
            $invite = $this->invitationModel->where('event_id', $event_id)
                                            ->where('guest_id', auth('api')->user()->id)
                                            ->firstOrFail();

            // User can only acccess their own pending or rejected invitations.
            if ($invite->status != 'confirmed' && $invite->status != $status) {
                // Update the invitation status
                $invite->update(['status' => $status]);

                $msg = $status == 'confirm' ? 'confirmed' : $status;
                return $this->responseResourceUpdated('Event invitation ' . $msg . '.');
            } else {
                return $this->responseUnauthorized();
            }
        } catch (Exception $e) {
            return $this->responseServerError('Error ' . ($status == 'rejected' ? 'rejecting' : 'confirming') . ' the invitation.');
        }
    }
}
