<?php
session_start();
require("session.php");
require("../mysql.php");
require("../se/php/verifyMem.php");
header('Content-Type: application/json; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    @$codetemp = $_POST["codetemp"];
    @$inputtemp = $_POST["inputtemp"];
    @$EID = $_POST["EID"];
    @$QID = $_POST["QID"];
    @$PID = $_POST["PID"];
    @$isMem = $_POST["isMem"];
    @$selectSearch=$_POST["selectSearch"];
    @$proLan=$_POST["proLan"];
    if ($codetemp != null) { 
       
    try{
	  shell_exec('sudo mkdir ../temp/'.$_SESSION[user]);
          shell_exec('sudo mkdir ../temp/'.$_SESSION[user].'/exam');
          shell_exec('sudo mkdir ../temp/'.$_SESSION[user].'/exam/'.$EID);
          shell_exec('sudo mkdir ../temp/'.$_SESSION[user].'/exam/'.$EID.'/'.$QID);
          if($isMem == 1 && strcmp($proLan,"C")==0){
            shell_exec('sudo cp ../se/c_head/verifyMem.h ../temp/'.$_SESSION[user].'/exam/'.$EID.'/'.$QID);

          }
	  $codepath="cd ../temp/".$_SESSION[user]."/exam/".$EID."/".$QID.";";
          shell_exec("sudo chown -R apache:apache ../temp/".$_SESSION[user]);

$codetemp=str_replace("<斜線>","/",$codetemp);
$codetemp=str_replace("\\\\","\\",$codetemp);
$codetemp=str_replace("''","'",$codetemp); 
if(strcmp($proLan,"CPP")==0){
$file = fopen("../temp/".$_SESSION[user]."/exam/".$EID."/".$QID."/code.cpp","w");
}
else
{
$file = fopen("../temp/".$_SESSION[user]."/exam/".$EID."/".$QID."/code.c","w");
  if($isMem == 1 && strcmp($proLan,"C")==0){
    $mem_file = fopen("../temp/".$_SESSION[user]."/exam/".$EID."/".$QID."/mem_code.c","w");
    $mem_codetemp = putVerifyContent($codetemp);
    fwrite($mem_file,$mem_codetemp);
    fclose($mem_file);
  }
}

$ck=strpos($codetemp,"/*");
$ck2=strpos($codetemp,"*/");
if(strcmp($proLan,""))
while($ck)
{
$codetemp=str_replace("/*","",$codetemp);
 
$ck=strpos($codetemp,"/*");
}
while($ck2)
{
$codetemp=str_replace("*/","",$codetemp);
 
$ck2=strpos($codetemp,"*/");
}
fwrite($file,$codetemp);
       fclose($file);
$codeoutput="輸入：<br>".str_replace("\n","<br>",$inputtemp)."<br>";
                                                                                $file1 = fopen("../temp/".$_SESSION[user]."/exam/".$EID."/".$QID."/input.txt","w");
                                                                                //$strtemp=str_replace("\\\\","\\",$_POST[]);
                                                                                
$str123=str_replace("\r\n","\n",$inputtemp);
fwrite($file1,$str123);
                                                                                fclose($file1);

$sql = "select * from PapersQuestionTest,PapersQuestion where PapersQuestionTest.ID=PapersQuestion.ID and PID='$PID' AND QID='$QID'";
$result = $conn2->query($sql);
if($result->num_rows>0)
{
$i=0;
    while($row = $result->fetch_assoc())
    {                                        
      $testOutput[$i]=str_replace('\'','\\\'',$row[tOut]);
      $i++;
    }
}
                                                                                
                                                                                for($i=0;$i<count($testOutput);$i++){
                                                                                        if(strcmp($selectSearch,"$i")==0){
                                                                                                $file1 = fopen("../temp/".$_SESSION[user]."/exam/".$EID."/".$QID."/correctoutput.txt","w");
                                                                                                $temp = str_replace("\\n","\n",$testOutput[$i]);
                                                $temp = str_replace("\'","'",$temp);
                                                                                                fwrite($file1,$temp);
                                                                                                fclose($file1);
                                                                                        }
                                                                                }
                                                                                shell_exec($codepath."echo ' ' >output.txt");
                                                                                if(strcmp($proLan,"JAVA")==0){
                                                                                        $output = shell_exec($codepath.'sudo javac -encoding utf-8 '.$className.'.java 2>&1 1> /dev/null');//g++ .cpp //javac -encoding utf-8 code.java
//                                                                                      $output=shell_exec($codepath."timeout 5s java -Dfile.encoding=UTF-8 ".$className." < input.txt 2>&1 1> /dev/null");
                                                                                }else if(strcmp($proLan,"CPP")==0){
                                                                                        $output = shell_exec($codepath.'sudo g++ code.cpp -lm 2>&1 1> /dev/null');
                                                                                }else{
                                                                                        $output = shell_exec($codepath.'sudo cc code.c -lm 2>&1 1> /dev/null');
                                                                                }

                                                                                if(strcmp($proLan,"C")== 0 && $isMem == 1){
                                                                                  //identify memPaper
                                                                                  $mem_output = shell_exec($codepath.'sudo cc mem_code.c -lm 2>&1 1> /dev/null -o mem.out');
                                                                                  if(empty($mem_output)){
                                                                                    $mem_outputRun=shell_exec($codepath."timeout 3s ./mem.out < input.txt 2>&1 1> /dev/null");
                                                                                  }
                                                                                  if(empty($mem_output)){
                                                                                    shell_exec($codepath."timeout 3s ./mem.out < input.txt > mem_output.txt");//java -Dfile.encoding=UTF-8
                                                                                  }
                                                                                }

                                                                                if(empty($output)){
                                                                                        if(strcmp($proLan,"JAVA")==0){
                                                                                                $outputRun=shell_exec($codepath."timeout 3s java -Dfile.encoding=UTF-8 ".$className." < input.txt 2>&1 1> /dev/null");//java -Dfile.encoding=UTF-8
                                                                                        }else{
                                                                                                $outputRun=shell_exec($codepath."timeout 3s ./a.out < input.txt 2>&1 1> /dev/null");//java -Dfile.encoding=UTF-8
                                                                                        }
                                                                                        if(empty($outputRun)){
                                                                                                $S1=microtime(true);
                                                                                                if(strcmp($proLan,"JAVA")==0){
                                                                                                        shell_exec($codepath."timeout 3s java -Dfile.encoding=UTF-8 ".$className." < input.txt > output.txt");//java -Dfile.encoding=UTF-8
                                                                                                }else{
                                                                                                        shell_exec($codepath."timeout 3s ./a.out < input.txt > output.txt");//java -Dfile.encoding=UTF-8
                                                                                                }
                                                                                                $S2=microtime(true);
                                                                                                $txtSize = shell_exec($codepath.'du output.txt');
                                                                                                $txtSize = str_replace("        output.txt","",$txtSize);
                                                                                                if(($txtSize<100)&&(($S2-$S1)<3)){
                                                                                                        $output = shell_exec($codepath.'cat output.txt');
                                                                                                        $output = str_replace(" ","&nbsp;",$output);
                                                                                                }else{
                                                                                                        $output = "<font color='red'>無限迴圈或演算法過長</font><br>";
                                                                                                }
                                                                                                //$output = str_replace("\t","tab",$output);
                                                $output = str_replace("&nbsp;","<img src='../upimages/spaceImg.png' height='8' />",$output);
                                                                                                $output = str_replace("\n","<img src='../upimages/enterImg.png' width='20' height='20' /><br>",$output);
                                                                                        }else{
                                                                                                $output=$outputRun;
                                                                                        }
                                                                                }
                                                                                $output = str_replace("\n","<br>",$output);
                                                                                $codeoutput=$codeoutput."<br>你的輸出：<br>".$output;
                                                                                
                                                                                if(strcmp($selectSearch,"end")!=0){
                                                                                       $codeoutput=$codeoutput."<br>系統判定：";
                                                                                        
                                                                                        if($txtSize<100){
                                                                                          if(strcmp($proLan,"C")== 0 && $isMem == 1){
                                                                                            $output = shell_exec($codepath.'sudo diff mem_output.txt correctoutput.txt');
                                                                                          }else{
                                                                                            $output = shell_exec($codepath.'sudo diff output.txt correctoutput.txt');
                                                                                          }
                                                                                                
                                                                                                if(empty($output)){
                                                                                                        $codeoutput=$codeoutput."<font color='#0000FF'>通過</font>";
                                                                                                }else{
                                                                                                        $codeoutput=$codeoutput."<font color='#FF0000'>未通過</font>";
                                                                                                }
                                                                                        }else{   
                                                                                                $codeoutput=$codeoutput."<font color='#FF0000'>未通過</font>";
                                                                                        }
                                                                                }
                                                                                                        
             $codeoutput=$codeoutput."(執行時間：".round($S2-$S1,4)."秒)";
             echo json_encode(array(
            	'codetemp' => $codeoutput,
        	));
	     }catch (Exception $e){
             }
    } else {
        echo json_encode(array(
            'errorMsg' => '資料未輸入完全！'
        ));
    }
} else {
    echo json_encode(array(
        'errorMsg' => '請求無效，只允許 POST 方式訪問！'
    ));
}
?>
