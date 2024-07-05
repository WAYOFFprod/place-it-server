<?php

namespace App\Http\Controllers;

use App\Enums\CanvasRequestType;
use App\Http\Requests\AddColorRequest;
use App\Http\Requests\CreateCanvasRequest;
use App\Http\Requests\DeleteCanvaRequest;
use App\Http\Requests\GetCanvaRequest;
use App\Http\Requests\GetCanvasRequest;
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
                $canvas = Canva::community()->limit(10)->get();
                break;
            case CanvasRequestType::Personal->value:
                if(!empty($user)) {
                    $canvas = $user->canvas()->get();
                }
                break;

            default:
                # code...
                break;
        }

        return CanvaResource::collection($canvas);
    }

    public function createCanva(CreateCanvasRequest $request) {
        // DB::table('canvas')->truncate();
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

        $imageCreated = ImageService::createImage($canva->id, $canva->width, $canva->height);
        return new CanvaResource($canva);
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
        return response()->json([
            "status" => "success"
        ], 200);
    }
}
