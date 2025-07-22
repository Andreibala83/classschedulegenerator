<?php
$mysqli = new mysqli("localhost", "root", "", "class_schedule");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

$fontPath = __DIR__ . '/OpenSans-Bold.ttf';
if (!file_exists($fontPath)) die('Font file not found.');

$width = 720;
$height = 1400;

// Load background image
if (isset($_FILES['custom_bg']) && $_FILES['custom_bg']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['custom_bg']['tmp_name'];
    $bg = @imagecreatefromjpeg($tmpName) ?: imagecreatefrompng($tmpName);
    if (!$bg) die("Invalid image.");

    $img = imagecreatetruecolor($width, $height);
    imagecopyresampled($img, $bg, 0, 0, 0, 0, $width, $height, imagesx($bg), imagesy($bg));
    imagedestroy($bg);
} else {
    $img = imagecreatetruecolor($width, $height);
    $bgColor = imagecolorallocate($img, 0, 0, 0);
    imagefill($img, 0, 0, $bgColor);
}

imagealphablending($img, true);
imagesavealpha($img, true);

// Get average brightness to determine text color
function getAvgBrightness($img, $samples = 100) {
    $w = imagesx($img);
    $h = imagesy($img);
    $total = 0;
    for ($i = 0; $i < $samples; $i++) {
        $x = rand(0, $w - 1);
        $y = rand(0, $h - 1);
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        $total += (0.299 * $r + 0.587 * $g + 0.114 * $b);
    }
    return $total / $samples;
}

$avgBrightness = getAvgBrightness($img);
$mainColor = $avgBrightness > 160
    ? imagecolorallocate($img, 0, 0, 0)
    : imagecolorallocate($img, 255, 255, 255);
$shadowColor = $avgBrightness > 160
    ? imagecolorallocate($img, 255, 255, 255)
    : imagecolorallocate($img, 0, 0, 0);

// Draw text with shadow
function drawTextWithShadow($img, $size, $angle, $x, $y, $shadowColor, $mainColor, $font, $text) {
    $offsets = [-2, -1, 0, 1, 2];
    foreach ($offsets as $ox) {
        foreach ($offsets as $oy) {
            if ($ox === 0 && $oy === 0) continue;
            imagettftext($img, $size, $angle, $x + $ox, $y + $oy, $shadowColor, $font, $text);
        }
    }
    imagettftext($img, $size, $angle, $x, $y, $mainColor, $font, $text);
}

// Calculate the max font size that fits within a given width
function fitFontSize($text, $maxWidth, $maxFontSize, $minFontSize, $fontPath) {
    for ($size = $maxFontSize; $size >= $minFontSize; $size--) {
        $box = imagettfbbox($size, 0, $fontPath, $text);
        $textWidth = abs($box[2] - $box[0]);
        if ($textWidth <= $maxWidth) {
            return $size;
        }
    }
    return $minFontSize;
}

// Layout settings
$dayFontSize = 20;
$titleSize = 38;
$leftX = 80;
$rightX = 400;
$yStart = 160;
$boxHeight = 90;
$spacingBetweenDays = 340;
$maxLineWidth = 280; // max width for text content in px

// Draw title
drawTextWithShadow($img, $titleSize, 0, 150, 100, $shadowColor, $mainColor, $fontPath, "CLASS SCHEDULE");

// Fetch and group schedule
$result = $mysqli->query("SELECT * FROM schedule ORDER BY FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), start_time");
$days = ['Monday'=>[], 'Tuesday'=>[], 'Wednesday'=>[], 'Thursday'=>[], 'Friday'=>[], 'Saturday'=>[]];
while ($row = $result->fetch_assoc()) {
    $days[$row['day']][] = $row;
}

// Draw each day
$dayIndex = 0;
foreach ($days as $day => $entries) {
    $isLeft = $dayIndex < 3;
    $x = $isLeft ? $leftX : $rightX;
    $y = $yStart + ($dayIndex % 3) * $spacingBetweenDays;

    drawTextWithShadow($img, $dayFontSize, 0, $x, $y, $shadowColor, $mainColor, $fontPath, strtoupper($day));
    $y += 34;

    foreach ($entries as $entry) {
        $timeText = $entry['start_time'] . " - " . $entry['end_time'];
        $timeFontSize = fitFontSize($timeText, $maxLineWidth, 18, 10, $fontPath);
        drawTextWithShadow($img, $timeFontSize, 0, $x + 10, $y + 28, $shadowColor, $mainColor, $fontPath, $timeText);

        $details = $entry['subject'] . " @ " . $entry['room'];
        if (!empty($entry['instructor'])) {
            $details .= " - " . $entry['instructor_title'] . " " . $entry['instructor'];
        }
        $detailsFontSize = fitFontSize($details, $maxLineWidth, 18, 10, $fontPath);
        drawTextWithShadow($img, $detailsFontSize, 0, $x + 10, $y + 54, $shadowColor, $mainColor, $fontPath, $details);

        $y += $boxHeight;
    }

    $dayIndex++;
}

// Save image
imagejpeg($img, "preview.jpg", 90);
imagedestroy($img);
header("Location: preview.php?" . time());
exit;
?>
