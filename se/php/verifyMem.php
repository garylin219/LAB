<?php
function changeMemVariable($data){
  $regex = "/&[a-zA-z\d\*$]*/";
  $matchStr = [];
  foreach($data as $key => $value){
    if(preg_match_all($regex,$value,$matchStr)){
      $originData = $matchStr[0];
      foreach($originData as $originVarible){
        $replaceVariable = "__address__(".$originVarible.")";
        $data[$key] = str_replace($originVarible,$replaceVariable,$data[$key]);
      }
    }
  }
  return $data;
}

function changeMemAddress($codeString){
  $regex = "/(?<=printf)\(.*\W*\S*\)/";
  $data = [];
  if(preg_match_all($regex,$codeString,$data)){
    $originData = $data[0];
    $replaceData = changeMemVariable($originData);
    $arrayData = array_combine($originData,$replaceData);
    foreach($arrayData as $origin => $replace){
      $codeString = str_replace($origin,$replace,$codeString);
    }
  }
  return $codeString;
}

function putVerifyContent($codeString){
  $regex = "/(main\(.*\)\s*{)/";
  $temp=[];
  $memoryContent = "#include \"verifyMem.h\"\n";
    if(preg_match($regex,$codeString,$temp)){
      $matchString = $temp[1];
      $start = strpos($codeString,$matchString);
      $length = strlen($matchString);
  
      $subString = substr($codeString,$start,$length);
      $initMemory = "int __firstLocal__;
          __stack__=&__firstLocal__;
          __heap__=malloc(1);";
      $initString = $subString.$initMemory;
      //echo $initString; 
      $codeString = str_replace($subString,$initString,$codeString);
      //echo $codeString;
      $codeString = changeMemAddress($codeString);
    }
  return $memoryContent.$codeString;
}

function removeVerifyContent($codeString){
  $memoryContent = "#include \"verifyMem.h\"\n";
  $initMemory = "int __firstLocal__;
          __stack__=&__firstLocal__;
          __heap__=malloc(1);";
  $codeString = str_replace($memoryContent,"",$codeString);
  $codeString = str_replace($initMemory,"",$codeString);
  $codeString = removeMemAddress($codeString);
  return $codeString;
}

function removeMemAddress($codeString){
  $regex = "/__address__\(&[a-zA-Z\d\*$]*\)/";
  $data = [];
  if(preg_match_all($regex,$codeString,$data)){
    $originData = $data[0];
    $replaceData = removeMemVarible($originData);
    $arrayData = array_combine($originData,$replaceData);
    foreach($arrayData as $origin => $replace){
      //echo "ori = ".$origin." replace = ".$replace."<br>";
      $codeString = str_replace($origin,$replace,$codeString);
    }
  }
  return $codeString; 
}

function removeMemVarible($data){ 
  $regex = "/&[a-zA-z\d\*$]*/";//__address__("&x")
  $matchStr = [];
  foreach($data as $key => $value){
    if(preg_match($regex,$value,$matchStr)){
      $replaceVarible = $matchStr[0];
      $data[$key] = $replaceVarible;
    }
  }
  return $data; 
}
?>
