<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddColorRequest;
use App\Http\Requests\CreateCanvasRequest;
use App\Http\Requests\GetCanvaRequest;
use App\Http\Requests\PlacePixelRequest;
use App\Http\Requests\PlacePixelsRequest;
use App\Models\Canva;
use App\Services\ImageService;

class CanvaController extends Controller
{
    public function getCanva(GetCanvaRequest $request, $id) {
        //todo: return image base64
        $canva = Canva::find($id);
        return response()->json([
            "width" => $canva->width,
            "height" => $canva->height,
            "colors" => $canva->colors,
            "image" => ImageService::getBase64Image($id),
        ]);
    }

    public function createCanva(CreateCanvasRequest $request) {
        $canva = Canva::find(1);

        // $canva = Canva::create([
        //     "width" => $request->width,
        //     "height" => $request->height
        // ]);

        $imageCreated = ImageService::createImage($canva->id, $canva->width, $canva->height);
        return $imageCreated;
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
            function (string $pixel) use($availableColors) {
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
