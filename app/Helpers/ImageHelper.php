<?php

if (!function_exists('addTextToImage')) {
    function addTextToImage($imagePath, $text, $fontSize, $x, $y, $color)
{
    // Determine the image type
    $imageType = exif_imagetype($imagePath);
    
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($imagePath);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($imagePath);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($imagePath);
            break;
        default:
            throw new \Exception("Unsupported image type: $imagePath");
    }
    
    if (!$image) {
        throw new \Exception("Could not create image from path: $imagePath");
    }

    // Allocate text color
    $textColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);

    // Add text to image
    imagestring($image, $fontSize, $x, $y, $text, $textColor);

    // Define a directory to save the modified image
    $saveDir = public_path('storage/modified_images');
    
    // Create the directory if it does not exist
    if (!file_exists($saveDir)) {
        mkdir($saveDir, 0755, true);
    }

    // Generate a unique filename for the new image
    $newImagePath = $saveDir . '/' . uniqid() . '.jpg';
    
    // Save the new image
    imagejpeg($image, $newImagePath);

    // Free up memory
    imagedestroy($image);

    return $newImagePath;
}

}
