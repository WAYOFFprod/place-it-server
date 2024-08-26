<?php

namespace App\Http\Controllers;

use App\Enums\CanvasRequestType;
use App\Http\Requests\AddColorRequest;
use App\Http\Requests\CreateCanvasRequest;
use App\Http\Requests\DeleteCanvaRequest;
use App\Http\Requests\GetCanvaRequest;
use App\Http\Requests\GetCanvasRequest;
use App\Http\Requests\JoinCanvaRequest;
use App\Http\Resources\CanvaResource;
use App\Http\Requests\PlacePixelsRequest;
use App\Http\Requests\ToggleLikeCanvaRequest;
use App\Models\Canva;
use App\Services\ImageService;
use Auth;
use Illuminate\Support\Facades\Http;
use App\Enums\CanvaVisibility;
use App\Enums\CanvaAccess;
use App\Http\Requests\UpdateCanvaRequest;
use App\Http\Resources\ParticipationResource;
use Illuminate\Http\Request;
use Log;

class CanvaController extends Controller
{
    public function getCanva(GetCanvaRequest $request, $id) {
        $canva = Canva::find($id);
        if(empty($canva)) {
            return response()->json([
                'message' => 'canva does not exist',
                'status' => 404
            ], 404);
        }
        $user = Auth::user();

        // TODO: canva if accesible or owned

        if(empty($user)) {
            return new CanvaResource($canva);
        }

        // TODO: check if user has write permission
        $canEdit = true;
        if($canEdit) {

            return new CanvaResource($canva);
        }

        return new CanvaResource($canva);
    }
    public function getCanvas(GetCanvasRequest $request) {
        $user = Auth::user();
        $canvas = [];
        $query = null;
        switch ($request->scope) {
            case CanvasRequestType::Community->value:
                $query = Canva::query()->community();
                if($request->favorit) {
                    $query->favorit();
                }
                if($request->sort) {
                    $query->orderBy('updated_at', $request->sort);
                }
                if($request->search) {
                    $query->where('name', 'LIKE', '%'.$request->search.'%');
                }
                break;
            case CanvasRequestType::Personal->value:
                if(!empty($user)) {
                    $query = Canva::query()->where('user_id', $user->id);
                    if($request->favorit) {
                        $query->favorit();
                    }
                    if($request->sort) {
                        $query->orderBy('updated_at', $request->sort);
                    }
                    if($request->search) {
                        $query->where('name', 'LIKE', '%'.$request->search.'%');
                    }
                    $canvas = $query->limit(10)->get();
                } else {
                    $query = Canva::query()->where('user_id', null);
                }
                break;

            default:
                $query = Canva::query();
                break;
        }

        $canvas = $query->limit(10)->get();

        return CanvaResource::collection($canvas);
    }

    public function createCanva(CreateCanvasRequest $request) {

        $user = Auth::user();

        $canva = $user->canvas()->create([
            "name" => $request->name,
            "category" => $request->category,
            "access" => $request->access,
            "visibility" => $request->visibility,
            "width" => $request->width,
            "height" => $request->height,
            "colors" => $request->colors,
            'live_player_count' => 0,
        ]);

        $user->participates()->attach($canva->id,['status' => 'accepted']);

        $imageCreated = ImageService::createImage($canva->id, $canva->width, $canva->height);

        return new CanvaResource($canva);
    }

    public function update(UpdateCanvaRequest $request) {
        $user = Auth::user();

        if(empty($user)) {
            return response()->json([
                'message' => "not authorised"
            ], 403);
        }

        $canva = $user->canvas->find($request->id);

        if(empty($canva)) {
            return response()->json([
                'message' => "not authorised"
            ], 403);
        }

        $canva[$request->field] = $request->value;
        $canva->save();

        return new CanvaResource($canva);
    }

    public function joinCanva(JoinCanvaRequest $request, $id) {
        $canva = Canva::find($id);
        $user = Auth::user();

        if(empty($canva)) {
            return response()->json([
                'message' => 'nothing to see here',
                'id' => $canva->id
            ], 403);
        }
        $accessStatus = $canva->requestAccess($user);
        if($accessStatus) {
            return response()->json([
                'message' => 'requested',
                'accessStatus' => $accessStatus,
                'id' => $canva->id
            ], 200);
        } else {
            return response()->json([
                'message' => 'nothing to see here',
                'id' => $canva->id
            ], 403);
        }
    }

    public function toggleLike(ToggleLikeCanvaRequest $request) {
        $user = Auth::user();
        $added = $user->toggleLikeCanvas($request->canvaId);
        return response()->json([
            'message' => "canva ".($added ? 'added to favorit' : 'removed from favorit'),
            'added' => $added,
            'id' => $request->canvaId
        ]);
    }

    public function deleteCanva(DeleteCanvaRequest $request, $id) {
        // DB::table('canvas')->truncate();
        $user = Auth::user();

        $canva = $user->canvas()->find($id);

        $canva->delete();

        $path = ImageService::deleteImage($id);

        return response()->json([
            'message' => "deleted successfully",
            'id' => $id
        ]);
    }

    public function replaceColors(AddColorRequest $request) {
        $user = Auth::user();
        $canva = Canva::find($request->id);
        if(!$canva->isOwnedBy($user)) {
            return response()->json([
                'message' => 'not allowed',
                'statue' => 403
            ],403);
        }
        $canva->colors = $request->colors;
        $canva->save();
        return $canva;
    }

    public function updatePlayerCount(Request $request) {
        $canva = Canva::findOrFail($request->id);
        $canva->live_player_count = $request->playerCount;
        $canva->save();
    }

    public function placePixel(PlacePixelsRequest $request) {
        $canva = Canva::findOrFail($request->id);

        $availableColors = [...$canva->colors, '#ffffff'];

        $validPixels = array_filter(
            $request->pixels,
            function ($pixel) use($availableColors) {
                if (in_array($pixel, $availableColors)) {
                    return true;
                };
                return false;
            }
        );
        $colors = array_unique($validPixels);

        ImageService::updateImage($validPixels, $colors, $request->id);

        // update timestamp updated_at
        $canva->touch();

        return response()->json([
            "status" => "success"
        ], 200);
    }
}
