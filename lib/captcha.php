<?php
session_start();

define("CAPTCHA_STORAGE", "captcha");
define("SALT", "1$$0ul4ch4ncl4");

function captcha_gen()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $captchaString = '';

    for ($i = 0; $i < 5; $i++) {
        $captchaString .= $characters[rand(0, strlen($characters) - 1)];
    }

    $session_string = hash("sha256", SALT . $captchaString);

    unset($_SESSION[CAPTCHA_STORAGE]);
    $_SESSION[CAPTCHA_STORAGE] = $session_string;

    $IMG_WIDTH = 150;
    $IMG_HEIGHT = 50;

    $image = imagecreate($IMG_WIDTH, $IMG_HEIGHT);
    $background = imagecolorallocate($image, 255, 255, 255); // couleur de fond : blanc
    $textColor = imagecolorallocate($image, rand(100, 200), rand(100, 200), rand(100, 200)); // Couleur du texte aléatoire

    $text_posx = ($IMG_WIDTH / 2) - 10;
    $text_posy = ($IMG_HEIGHT / 2) + 10;
    imagettftext($image, 20, 0, $text_posx, $text_posy, $textColor, "fonts/Roboto.ttf", $captchaString);
    $bar_count = 10;
    for ($i = 0; $i < $bar_count; $i++) {
        $bar_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
        imageline($image, $text_posx, rand($text_posy, $text_posy + 30), rand(0, $IMG_WIDTH), rand(0, $IMG_HEIGHT), $bar_color);
    }
    $image = imagerotate($image, rand(10, 45), rand(0, 0xFFFFFF));

    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image);
}

function captcha_verify(string $answer)
{
    $captcha = $_SESSION[CAPTCHA_STORAGE];
    $answer_hashed = hash("sha256", SALT . $answer);

    return $answer_hashed == $captcha;
}

function captcha_clear()
{
    session_start();
    unset($_SESSION[CAPTCHA_STORAGE]);
}
