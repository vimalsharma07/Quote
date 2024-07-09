<?php

if (!function_exists('addTextToImage')) {
    function addTextToImage($imagePath, $text, $fontSize, $xPercent, $yPercent, $color, $align)
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

    // Get the image dimensions
    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);

    // Calculate the position based on percentages
    $x = ($xPercent / 100) * $imageWidth;
    $y = ($yPercent / 100) * $imageHeight;

    // Allocate text color
    $textColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);

    // Split the text into lines
    $lines = explode("\n", $text);
    $lineHeight = imagefontheight($fontSize);

    // Adjust the x position based on alignment
    foreach ($lines as &$line) {
        $lineWidth = imagefontwidth($fontSize) * strlen($line);
        switch ($align) {
            case 'center':
                $lineX = $x - ($lineWidth / 2);
                break;
            case 'right':
                $lineX = $x - $lineWidth;
                break;
            case 'left':
            default:
                $lineX = $x;
                break;
        }
        // Draw each line of text
        imagestring($image, $fontSize, $lineX, $y, $line, $textColor);
        $y += $lineHeight;
    }

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
