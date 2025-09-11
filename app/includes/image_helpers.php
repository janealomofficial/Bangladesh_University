<?php
// create_thumbnail($srcPath, $destPath, $maxW, $maxH)
function create_thumbnail($src, $dest, $maxW = 400, $maxH = 300) {
    $info = getimagesize($src);
    if (!$info) return false;
    list($w, $h) = $info;
    $mime = $info['mime'];

    // calculate new size
    $ratio = min($maxW/$w, $maxH/$h, 1);
    $nw = (int)($w * $ratio);
    $nh = (int)($h * $ratio);

    // create image resources
    switch ($mime) {
        case 'image/jpeg': $srcImg = imagecreatefromjpeg($src); break;
        case 'image/png':  $srcImg = imagecreatefrompng($src);  break;
        case 'image/gif':  $srcImg = imagecreatefromgif($src);  break;
        default: return false;
    }

    $dstImg = imagecreatetruecolor($nw, $nh);

    // preserve PNG transparency
    if ($mime === 'image/png') {
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
    }

    imagecopyresampled($dstImg, $srcImg, 0,0,0,0, $nw, $nh, $w, $h);

    // save
    switch ($mime) {
        case 'image/jpeg': imagejpeg($dstImg, $dest, 85); break;
        case 'image/png':  imagepng($dstImg, $dest); break;
        case 'image/gif':  imagegif($dstImg, $dest); break;
    }

    imagedestroy($srcImg);
    imagedestroy($dstImg);
    return true;
}
