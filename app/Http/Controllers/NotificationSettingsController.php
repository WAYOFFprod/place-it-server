<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateFieldRequest;
use App\Http\Resources\NotificationSettingsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingsController extends Controller
{
    public function get(Request $request) {
        $user = Auth::user();
        $settings = $user->notificationSettings()->first();
        if(empty($settings)) {
            $settings = $user->notificationSettings()->create([]);
        }
        return new NotificationSettingsResource($settings);
    }

    public function updatefield(UpdateFieldRequest $request) {
        $user = Auth::user();
        $settings = $user->notificationSettings()->first();
        if(empty($settings)) {
            return response()->json([
                'message' => 'missing notification settings',
                'code' => 404
            ], 404);
        }

        $settings[$request->field] = $request->value ? 1 : 0;
        $settings->save();
        return new NotificationSettingsResource($settings);
    }
}
