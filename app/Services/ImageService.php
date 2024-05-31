<?php
namespace App\Services;

class ImageService {
    public static function getBase64Image($id) : string
    {
        $path = self::getPath($id);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    public static function createImage($id, $width, $height) {
        $path = self::getPath($id);
        if (file_exists($path)) {
            unlink($path);
        }

        $file = fopen($path, 'w') or die("can't open file");
        fclose($file);

        $image = imagecreate($width, $height);
        // add white as first color as it will fill the background
        imagecolorallocate(
            $image,
            255,
            255,
            255
        );
        // TODO: create file before saving it

        $path = public_path('storage')."/canvas/".$id.".png";

        return imagepng(
            $image,
            $path
        );
    }

    public static function addColors(int $id, array $colors) {
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
            if($colorId) {
                $colorIds[$colorId] = $rgb;
            }
        }
        return $colorIds;
    }

    public static function hexToRgb(string $hex) {
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        return [
            "r" => $r,
            "g" => $g,
            "b" => $b
        ];
    }

    static function getPath(int $id) {
        return public_path('storage')."/canvas/".$id.".png";
    }

    public static function createImageWithPixelArray(Array $pixels, $width, $height) {
        $image = imagecreate($width, $height);

        foreach ($pixels as $i => $color) {
            $x = $i % $width;
            $y = floor($i / $width);
            $isPlaced = imagesetpixel(
                $image,
                $x,
                $y,
                $color
            );
        }

        $path = public_path('storage')."/image.png";
        // TODO: save image
        $isImageSaved = imagepng(
            $image,
            $path
        );

        dd($isImageSaved);
    }

    public static function updateImage(Array $pixels, Array $colors, int $id) {
        $path = self::getPath($id);
        $size = getimagesize($path);
        $image = imagecreatefrompng($path);
        $width = $size[0];

        $colorsIds = self::prepColors($image, $pixels, $colors);
        foreach ($pixels as $i => $color) {
            $x = $i % $width;
            $y = (int)floor((int)$i / $width);
            // dd($x,$y, $i / $width,);
            $isPlaced = imagesetpixel(
                $image,
                $x,
                $y,
                $colorsIds[$color]
            );
        }
        $isImageSaved = imagepng(
            $image,
            $path
        );
    }

    static function prepColors($image, array $pixels, array $colors) {
        $colorIds = [];
        foreach ($colors as $key => $color) {
            $rgb = self::hexToRgb($color);
            $colorId = imagecolorallocate(
                $image,
                $rgb['r'],
                $rgb['g'],
                $rgb['b']
            );
            $colorIds[$color] = $colorId;
        }
        return $colorIds;
    }

}
