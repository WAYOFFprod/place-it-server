<?php

namespace App\Http\Controllers;

use App\Enums\CanvaAccess;
use App\Enums\ParticipationStatus;
use App\Http\Requests\AddParticipantRequest;
use App\Http\Requests\RequestAccessRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Http\Resources\ParticipationResource;
use App\Models\Canva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipationController extends Controller
{
    public function invite(AddParticipantRequest $request)
    {
        $user = Auth::user();
        $canva = $user->canvas()->findOrFail($request->canva_id);

        return $canva->participates()->attach($request->user_id, [
            'status' => ParticipationStatus::Invited->value,
        ]);
    }

    public function requestAccess(RequestAccessRequest $request)
    {
        //TODO: check canva type to see if it allows requests
        $user = Auth::user();

        $canva = Canva::find($request->canva_id);
        if ($canva->access == CanvaAccess::RequestOnly->value) {
            return $canva->participates()->attach($user->id, [
                'status' => ParticipationStatus::Invited->value,
            ]);
        } elseif ($canva->access == CanvaAccess::Open->value) {
            return $canva->participates()->attach($user->id, [
                'status' => ParticipationStatus::Accepted->value,
            ]);
        }
    }

    public function acceptRequest(AddParticipantRequest $request)
    {
        $user = Auth::user();
        $canva = $user->canvas()->findOrFail($request->canva_id);

        $participationQuery = $canva->participates()->where('user_id', $request->user_id);
        $userWithParticipation = $participationQuery->first();
        if (empty($userWithParticipation)) {
            return response()->json([
                'message' => 'missing participation request',
                'status' => 404,
            ], 404);
        }

        $status = $userWithParticipation->pivot->status;
        if ($status == ParticipationStatus::Accepted->value || $status == ParticipationStatus::Invited->value) {
            return response()->json([
                'message' => "can't accept request",
                'status' => 403,
            ], 403);
        }

        if ($status == ParticipationStatus::Requested->value) {
            $participationQuery->updateExistingPivot($request->user_id, [
                'status' => ParticipationStatus::Accepted->value,
            ]);
        }

        if ($status == ParticipationStatus::Rejected->value) {
            $participationQuery->updateExistingPivot($request->user_id, [
                'status' => ParticipationStatus::Invited->value,
            ]);
        }

        $user = $canva->participates()->where('users.id', $request->user_id)->first();

        return new ParticipationResource($participationQuery->first());
    }

    public function rejectRequest(AddParticipantRequest $request)
    {
        $user = Auth::user();
        $canva = $user->canvas()->findOrFail($request->canva_id);

        $participationQuery = $canva->participates()->where('user_id', $request->user_id);
        $userWithParticipation = $participationQuery->first();
        if (empty($userWithParticipation)) {
            return response()->json([
                'message' => 'missing participation request',
                'status' => 404,
            ], 404);
        }
        $status = $userWithParticipation->pivot->status;
        if (
            $status == ParticipationStatus::Invited->value
            || $status != ParticipationStatus::Rejected->value
        ) {
            $participationQuery->updateExistingPivot($request->user_id, [
                'status' => ParticipationStatus::Rejected->value,
            ]);
        }
        $user = $canva->participates()->where('users.id', $request->user_id)->first();

        return new ParticipationResource($participationQuery->first());
    }

    public function getParticipants(Request $request, $id)
    {
        $user = Auth::user();
        $canva = $user->canvas()->findOrFail($id);

        return ParticipationResource::collection($canva->participates()->get()->except($user->id));
    }

    public function patchParticipant(UpdateParticipantRequest $request)
    {
        $status = $request->status;
        // $user = Auth::user();
        $canva = Canva::find($request->canva_id);
        $canva->participates()->updateExistingPivot($request->user_id, [
            'status' => $status,
        ]);
        $user = $canva->participates()->where('users.id', $request->user_id)->first();

        return new ParticipationResource($user);
    }
}
