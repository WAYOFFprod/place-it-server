<?php

namespace App\Http\Controllers;

use App\Enums\CanvasRequestType;
use App\Http\Requests\AddColorRequest;
use App\Http\Requests\CreateCanvasRequest;
use App\Http\Requests\DeleteCanvaRequest;
use App\Http\Requests\GetCanvaRequest;
use App\Http\Requests\GetCanvasRequest;
use App\Http\Requests\JoinCanvaRequest;
use App\Http\Requests\PlacePixelsRequest;
use App\Http\Requests\ToggleLikeCanvaRequest;
use App\Http\Requests\UpdateCanvaRequest;
use App\Http\Resources\CanvaResource;
use App\Models\Canva;
use App\Models\User;
use App\Services\ImageService;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Log;

class CanvaController extends Controller
{
    public function getCanva(GetCanvaRequest $request, $id)
    {
        $canva = Canva::find($id);
        if (empty($canva)) {
            return response()->json([
                'message' => 'canva does not exist',
                'status' => 404,
            ], 404);
        }
        $user = Auth::user();

        // TODO: canva if accesible or owned

        if (empty($user)) {
            return new CanvaResource($canva);
        }

        // TODO: check if user has write permission
        $canEdit = true;
        if ($canEdit) {

            return new CanvaResource($canva);
        }

        return new CanvaResource($canva);
    }

    public function getCanvas(GetCanvasRequest $request)
    {
        $user = Auth::user();
        $canvas = [];
        $query = null;
        switch ($request->scope) {
            case CanvasRequestType::Community->value:
                $query = $this->getCommunityCanvas($user, $request);
                break;
            case CanvasRequestType::Personal->value:
                $query = $this->getPersonalCanvas($user, $request);
                break;

            default:
                $query = Canva::query();
                break;
        }

        $canvas = $query->limit(10)->get();

        return CanvaResource::collection($canvas);
    }

    private function getCommunityCanvas(?User $user, Request $request): Builder
    {
        $query = Canva::query()->orderBy('updated_at', 'desc')->community();
        if ($request->favorit) {
            $query->favorit();
        }
        if ($request->sort) {
            $query->orderBy('updated_at', $request->sort);
        }
        if ($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        return $query;
    }

    private function getPersonalCanvas(?User $user, Request $request): Builder
    {
        // if authenticated
        $query = Canva::query();
        if (! empty($user)) {
            $query = Canva::query()->orderBy('updated_at', 'desc')->where('user_id', $user->id);
            if ($request->favorit) {
                $query->favorit();
            }
            if ($request->sort) {
                $query->orderBy('updated_at', $request->sort);
            }
            if ($request->search) {
                $query->where('name', 'LIKE', '%'.$request->search.'%');
            }
        }

        return $query;
    }

    public function createCanva(CreateCanvasRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Canva $canva */
        $canva = $user->canvas()->create([
            'name' => $request->name,
            'category' => $request->category,
            'access' => $request->access,
            'visibility' => $request->visibility,
            'width' => $request->width,
            'height' => $request->height,
            'colors' => $request->colors,
            'live_player_count' => 0,
        ]);

        $user->participates()->attach($canva->id, ['status' => 'accepted']);

        $imageCreated = ImageService::createImage($canva->id, $canva->width, $canva->height);

        return new CanvaResource($canva);
    }

    public function update(UpdateCanvaRequest $request)
    {
        /** @var User| null $user */
        $user = Auth::user();

        if (empty($user)) {
            return response()->json([
                'message' => 'not authorised',
            ], 403);
        }

        $canva = $user->canvas->find($request->id);

        if (empty($canva)) {
            return response()->json([
                'message' => 'not authorised',
            ], 403);
        }

        $canva[$request->field] = $request->value;
        $canva->save();

        return new CanvaResource($canva);
    }

    public function joinCanva(JoinCanvaRequest $request, $id)
    {
        $canva = Canva::find($id);
        $user = Auth::user();

        if (empty($canva)) {
            return response()->json([
                'message' => 'nothing to see here',
                'id' => $id,
            ], 403);
        }
        $accessStatus = $canva->requestAccess($user);
        if ($accessStatus) {
            return response()->json([
                'message' => 'requested',
                'accessStatus' => $accessStatus,
                'id' => $canva->id,
            ], 200);
        } else {
            return response()->json([
                'message' => 'nothing to see here',
                'id' => $canva->id,
            ], 403);
        }
    }

    public function toggleLike(ToggleLikeCanvaRequest $request)
    {
        $user = Auth::user();
        $added = $user->toggleLikeCanvas($request->canvaId);

        return response()->json([
            'message' => 'canva '.($added ? 'added to favorit' : 'removed from favorit'),
            'added' => $added,
            'id' => $request->canvaId,
        ]);
    }

    public function deleteCanva(DeleteCanvaRequest $request, $id)
    {
        // DB::table('canvas')->truncate();
        $user = Auth::user();

        $canva = $user->canvas()->find($id);

        $canva->delete();

        $path = ImageService::deleteImage($id);

        return response()->json([
            'message' => 'deleted successfully',
            'id' => $id,
        ]);
    }

    public function replaceColors(AddColorRequest $request)
    {
        $user = Auth::user();
        $canva = Canva::find($request->id);
        if (! $canva->isOwnedBy($user)) {
            return response()->json([
                'message' => 'not allowed',
                'statue' => 403,
            ], 403);
        }
        $canva->colors = $request->colors;
        $canva->save();

        return $canva;
    }

    public function updatePlayerCount(Request $request)
    {
        $canva = Canva::findOrFail($request->id);
        $canva->live_player_count = $request->playerCount;
        $canva->save();

        return response()->json(201);
    }

    public function placePixel(PlacePixelsRequest $request)
    {
        Log::info('Place pixel request received for canvas '.$request->id.' with '.count($request->pixels).' pixels.');
        $canva = Canva::findOrFail($request->id);

        $canvaColors = is_array($canva->colors) ? $canva->colors : json_decode($canva->colors, true);
        $availableColors = [...$canvaColors, '#ffffff'];

        $validPixels = array_filter(
            $request->pixels,
            function ($pixel) use ($availableColors) {
                if (in_array($pixel, $availableColors)) {
                    return true;
                }

                return false;
            }
        );
        $colors = array_unique($validPixels);
        Log::info('Updating image with colors: '.json_encode($colors));

        ImageService::updateImage($validPixels, $colors, $request->id);

        // update timestamp updated_at
        $canva->touch();

        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
