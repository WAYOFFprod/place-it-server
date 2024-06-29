<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddColorRequest;
use App\Http\Requests\CreateCanvasRequest;
use App\Http\Requests\GetCanvaRequest;
use App\Http\Resources\CanvaResource;
use App\Http\Requests\PlacePixelsRequest;
use App\Models\Canva;
use App\Services\ImageService;
use Auth;
use DB;
use Request;

class CanvaController extends Controller
{
    public function getCanva(GetCanvaRequest $request, $id) {
        //todo: return image base64
        $canva = Canva::find($id);
        return response()->json([
            "id" => $canva->id,
            "width" => $canva->width,
            "height" => $canva->height,
            "colors" => $canva->colors,
            "image" => ImageService::getBase64Image($id),
        ]);
    }
    public function getCanvas(Request $request) {
        $user = Auth::user();
        $canvas = [];
        if(!empty($user)) {
            $canvas = $user->canvas()->get();
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
