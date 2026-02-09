<?php

namespace App\Services;

use Log;

class ImageService
{
    public static function getBase64Image($id): string
    {
        $path = self::getPath($id);

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:image/'.$type.';base64,'.base64_encode($data);
    }

    public static function createImage($id, $width, $height)
    {
        $path = ImageService::deleteImage($id);

        $file = fopen($path, 'w') or exit("can't open file");
        fclose($file);

        $image = imagecreate($width, $height);
        // add white as first color as it will fill the background
        imagecolorallocate(
            $image,
            255,
            255,
            255
        );

        return imagepng(
            $image,
            $path
        );
    }

    public static function deleteImage(int $id)
    {
        $path = self::getPath($id);
        if (file_exists($path)) {
            unlink($path);
        }

        return $path;
    }

    public static function addColors(int $id, array $colors)
    {
        $image = imagecreatefrompng(self::getPath($id));

        $colorIds = [];
        foreach ($colors as $key => $color) {
            $rgb = self::hexToRgb($color);
            $colorId = imagecolorallocate(
                $image,
                $rgb['r'],
                $rgb['g'],
                $rgb['b']
            );
            if ($colorId) {
                $colorIds[$colorId] = $rgb;
            } else {
                Log::info('Color not added: '.$color.' : '.$colorId);
            }
        }

        return $colorIds;
    }

    public static function hexToRgb(string $hex)
    {
        [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');

        return [
            'r' => $r,
            'g' => $g,
            'b' => $b,
        ];
    }

    public static function getPath(int $id)
    {
        return config('filesystems.disks.canvas.root').'/'.$id.'.png';
    }

    public static function updateImage(array $pixels, array $colors, int $id)
    {
        $path = self::getPath($id);
        $size = getimagesize($path);
        $image = imagecreatefrompng($path);
        $width = $size[0];

        $colorsIds = self::prepColors($image, $colors);
        Log::info($colorsIds);
        foreach ($pixels as $i => $color) {
            $x = $i % $width;
            $y = (int) floor((int) $i / $width);
            // dd($x,$y, $i / $width,);
            $isPlaced = imagesetpixel(
                $image,
                $x,
                $y,
                $colorsIds[$color]
            );
            Log::info($color);
            Log::info($colorsIds[$color]);
        }
        $isImageSaved = imagepng(
            $image,
            $path
        );
    }

    // TODO: There seems to be an issue when having too many colors, need to figure out how to dealocate colors if necessary
    public static function prepColors($image, array $colors)
    {
        $colorIds = [];
        foreach ($colors as $key => $color) {
            $rgb = self::hexToRgb($color);
            $colorId = imagecolorallocate(
                $image,
                $rgb['r'],
                $rgb['g'],
                $rgb['b']
            );
            if ($colorId) {
                $colorIds[$color] = $colorId;
            } else {
                Log::info('Color not added: '.$color.' : '.$colorId);
            }
        }

        return $colorIds;
    }
}
