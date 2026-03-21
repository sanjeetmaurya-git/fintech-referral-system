<?php
$img = imagecreatetruecolor(100, 100);
$white = imagecolorallocate($img, 255, 255, 255);
imagefill($img, 0, 0, $white);
imagepng($img, 'test_img.png');
echo "Created test_img.png";
