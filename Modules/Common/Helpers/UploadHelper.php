<?php


namespace Modules\Common\Helpers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait UploadHelper
{
    public function upload($imageFromRequest, $imageFolder, $resize = false, $minimize = false): string
    {
        if (!file_exists(public_path('uploads/' . $imageFolder))) {
            mkdir(public_path('uploads/' . $imageFolder), 0777, true);
        }

        $extension = strtolower($imageFromRequest->getClientOriginalExtension());
        $fileName  = time() . '_' . uniqid() . '.' . $extension;
        $location  = public_path('uploads/' . $imageFolder . '/' . $fileName);

        $manager = new ImageManager(new Driver());
        $image   = $manager->read($imageFromRequest->getPathname());

        if ($resize) {
            $image->scaleDown(500, 500);
        }

        if ($minimize) {
            $image->scaleDown(300, 300);
        }

        $quality = 50;

        match ($extension) {
            'png'  => $image->toPng()->save($location),
            'webp' => $image->toWebp($quality)->save($location),
            'gif'  => $image->toGif()->save($location),
            default => $image->toJpeg($quality)->save($location),
        };

        return $fileName;
    }

    public function uploadFile($fileFromRequest, $fileFolder)
    {
        $fileName = time() . '.' . $fileFromRequest->getClientOriginalName();
        $location = public_path('uploads/' . $fileFolder . '/');
        $fileFromRequest->move($location, $fileName);

        return $fileName;
    }

    public function getImageName($folderName, $imagePath)
    {
        $needle = $folderName . '/';
        return substr($imagePath, strpos($imagePath, $needle) + strlen($needle));
    }
}
