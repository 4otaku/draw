<?

$width = (int) $_GET['width'];
$height = (int) $_GET['height'];

$image = imagecreatetruecolor($width, $height);

$white = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $white);

header('Content-type: image/png');
imagepng($image);
