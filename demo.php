<html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("lib/captcha.php");
    if (captcha_verify($_POST["captcha_try"])) {
        echo "Well played";
    } else {
        echo "Auth failed";
    }
}
?>
<img src="gen_captcha.php" />
<form action="test_captcha.php" method="post">
    <input type="text" name="captcha_try" />
    <input type="submit" />
</form>

</html>
