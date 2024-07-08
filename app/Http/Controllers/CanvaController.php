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
use App\Models\Canva;
use App\Services\ImageService;
use Auth;

class CanvaController extends Controller
{
    public function getCanva(GetCanvaRequest $request, $id) {
        //todo: return image base64
        $canva = Canva::find($id);
        return new CanvaResource($canva);
    }
    public function getCanvas(GetCanvasRequest $request) {
        $user = Auth::user();
        $canvas = [];
        switch ($request->scope) {
            case CanvasRequestType::Community->value:
                $canvas = Canva::community()->orderBy('updated_at','desc')->limit(10)->get();
                break;
            case CanvasRequestType::Personal->value:
                if(!empty($user)) {
                    $canvas = $user->canvas()->orderBy('updated_at','desc')->limit(10)->get();
                }
                break;

            default:
                # code...
                break;
        }

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
            "colors" => $request->colors
        ]);

        $user->participates()->attach($canva->id,['status' => 'accepted']);

        $imageCreated = ImageService::createImage($canva->id, $canva->width, $canva->height);

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

    public function addColors(AddColorRequest $request) {
        $canva = Canva::find($request->id);
        $canva->colors = $request->colors;
        $canva->save();
        return $canva;
    }

    public function placePixel(PlacePixelsRequest $request) {
        $canva = Canva::find($request->id);

        $availableColors = $canva->colors;

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
