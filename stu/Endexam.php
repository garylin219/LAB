<?php
require("session.php");
require("../mysql.php");
require("../se/php/verifyMem.php");
//error_reporting(0);  
set_time_limit(0);
ob_end_clean();
ob_start();
$isMemory = 0;  
//$scorestr=0;  
//$z=0;
$sql = "select * from ExamLog where status ='結束' and username='$_SESSION[user]' and EID='$_GET[EID]'";
$result = $conn2->query($sql);
if($result->num_rows==0){
  $sql = "INSERT INTO ExamLog( EID , username , status , action , IP)
    VALUES('$_GET[EID]' , '$_SESSION[user]' , '結束' , '結束考試' , '$myip')";
  $conn2->query($sql);
 
  /*$sql = "select * from Exams where EID ='$_GET[EID]'";
  $result = $conn2->query($sql);
  $row = $result->fetch_assoc();
  $PID=$row[PID];*/
  $sqlQ = "select * from PapersScore where PID ='$_GET[PID]' order by queue";
  $resultQ = $conn2->query($sqlQ);
  if($resultQ->num_rows>0){
    $z=0;
    while($rowQ = $resultQ->fetch_assoc()){
      $temp[$z]=$rowQ[QID];
      $tempPaperScore[$z]=$rowQ[score];
      $z++;
    }
  }
  $k=0;
  $miss=0;
  //$kA=0;
//題目迴圈
  $totaltemp=0;
  $determine=0;
  for($j=0;$j<$z;$j++){
    $flush ='<script>parent.p("'.round(($j)/count($temp)*100).'");</script>';echo $flush;ob_flush();flush();
    $sql = "select * from PapersQuestion where PID='$_GET[PID]' AND QID='$temp[$j]'";
    $result = $conn2->query($sql);
    $row = $result->fetch_assoc();
    $proLan=$row[proLan];
    $className=$row[className];
    $count=shell_exec("ls /var/www/html/exampaper/".$_GET[PID]."/".$temp[$j]." -l  |grep \"^-\"|wc -l");
    /*$sqlT = "select * from PapersQuestionTest where ID='$row[ID]'";    
    $resultT = $conn2->query($sqlT);
    if($resultT->num_rows>0){
      unset($testOutput);
      unset($testInput);
      unset($testScore);
      $x=0;
      while($rowT = $resultT->fetch_assoc()){
    $testOutput[$x]=$rowT[tOut];
    $testInput[$x]=$rowT[tIn];
    $testScore[$x]=$rowT[score];
    $x++;
    //$kA++;
      }
    }*/
    //$kA--;
    //$scorestrtemp=0;
    //測試檔迴圈
    for($i=0;$i<($count/2);$i++,$k++){
      //$flush ='<script>parent.p("'.round($k/$kA*100).'");</script>';echo $flush;ob_flush();flush();
      $codepath="cd /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/;";
      //shell_exec("sudo chown -R apache:apache /var/www/html/temp");
      //shell_exec('sudo mkdir /var/www/html/temp/'.$_SESSION[user]);
      //$codepath="cd /var/www/html/temp/".$_SESSION[user].";";
      //shell_exec("sudo chown -R apache:apache /var/www/html/temp");
      //shell_exec("sudo chown -R apache:apache /var/www/html/temp/".$_SESSION[user]);
      /*if(strcmp($proLan,"JAVA")==0){
    $file = fopen("/var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/".$className.".java","w");
    }else if(strcmp($proLan,"CPP")==0){
    $file = fopen("/var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/code.cpp","w");
    }else{
    $file = fopen("/var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/code.c","w");
    }*/
 
      if(is_dir("/var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j])){
    /*$file1 = fopen("/var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/input.txt","w");
    $intemp = str_replace("\\n","\n",$testInput[$i]);
    fwrite($file1,$intemp);
    fclose($file1);
 
    $file1 = fopen("/var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/correctoutput.txt","w");
    $outtemp = str_replace("\\n","\n",$testOutput[$i]);
    fwrite($file1,$outtemp);
    fclose($file1);*/
    shell_exec($codepath."echo ' ' >output.txt");
    if(strcmp($proLan,"JAVA")==0){
      $output = shell_exec("sudo javac -encoding utf-8 /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/".$className.".java 2>&1 1> /dev/null");//g++ .cpp //javac -encoding utf-8 code.java
    }else if(strcmp($proLan,"CPP")==0){
      $output = shell_exec("sudo g++ /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/code.cpp -lm 2>&1 1> /dev/null");
    }else{
      $sql = "SELECT mem_adr FROM QuestionTags WHERE QID=".$temp[$j];
      $result = $conn1->query($sql);
      $mem = $result->fetch_assoc();
      $isMemory = $mem[mem_adr];
      if($isMemory == 1){
        $output = shell_exec("sudo cc /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/mem_code.c -lm 2>&1 1> /dev/null -o mem.out");
      }else{
        $output = shell_exec("sudo cc /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$temp[$j]."/code.c -lm 2>&1 1> /dev/null");
      }
      //$output = shell_exec($codepath.'sudo cc code.c -lm 2>&1 1> /dev/null');
      
    }
    if(empty($output)){
      $S1=microtime(true);
      if(strcmp($proLan,"JAVA")==0){
        shell_exec($codepath."timeout 3s java -Dfile.encoding=UTF-8 ".$className." < /var/www/html/exampaper/".$_GET[PID]."/".$temp[$j]."/input".($i+1).".txt > output.txt");//java -Dfile.encoding=UTF-8
      }else{
        if($isMemory == 1 && strcmp($proLan,"C")==0){
          shell_exec($codepath."timeout 3s ./mem.out < /var/www/html/exampaper/".$_GET[PID]."/".$temp[$j]."/input".($i+1).".txt > mem_output.txt");
        }else{
          shell_exec($codepath."timeout 3s ./a.out < /var/www/html/exampaper/".$_GET[PID]."/".$temp[$j]."/input".($i+1).".txt > output.txt");//java -Dfile.encoding=UTF-8
        }
      }
      $S2=microtime(true);
      $txtSize = shell_exec($codepath.'du output.txt');
      $txtSize = str_replace("    output.txt","",$txtSize);
      if(($txtSize<100)&&(($S2-$S1)<3)){
        if($isMemory == 1 && strcmp($proLan,"C")==0){
          $output = shell_exec($codepath.'sudo diff mem_output.txt /var/www/html/exampaper/'.$_GET[PID].'/'.$temp[$j].'/output'.($i+1).'.txt');
        }else{
          $output = shell_exec($codepath.'sudo diff output.txt /var/www/html/exampaper/'.$_GET[PID].'/'.$temp[$j].'/output'.($i+1).'.txt');
        }
        
        if(empty($output)){
          echo "第".($j+1)."題-第".($i+1)."個測試檔-<font color='blue'>通過</font><br>";
          //$scorestr+=($tempPaperScore[$j]*($testScore[$i]/100));
          //$scorestrtemp+=($tempPaperScore[$j]*($testScore[$i]/100));
          $qscore=1;
        }else{
          echo "第".($j+1)."題-第".($i+1)."個測試檔-<font color='red'>未通過</font><br><br>------------------------------------------------------------------------------<br><br>";
          $qscore=-1;
          shell_exec("echo -e '第".($j+1)."題-第".($i+1)."個測試檔未通過\n' >>  /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/grade_log.txt");
          break;
        }
      }else{
        $determine=1;
        $qscore=-3;
        echo "第".($j+1)."題-第".($i+1)."個測試檔-<font color='red'>未通過[執行時間過長]</font><br><br>------------------------------------------------------------------------------<br><br>";
        shell_exec("echo -e '第".($j+1)."題-第".($i+1)."個測試檔未通過[執行時間過長]\n' >>  /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/grade_log.txt");
        break;
      }
    }else{
      $qscore=-2;
      echo "第".($j+1)."題-<font color='red'>編譯錯誤</font><br><br>------------------------------------------------------------------------------<br><br>";
      shell_exec("echo -e '第".($j+1)."題-編譯錯誤\n' >>  /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/grade_log.txt");
      break;
    }
      }else{
    $qscore=0;
    echo "第".($j+1)."題-<font color='red'>無作答</font><br><br>------------------------------------------------------------------------------<br><br>";
    shell_exec("echo -e '第".($j+1)."題-無作答\n' >>  /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/grade_log.txt");
    break;
      }
    }
    //測試檔迴圈結束
    //echo "<br>";
    //寫入資料庫顯示通過
    if(strcmp($qscore,"-3")==0){
      $sqlT = "INSERT INTO ExamQuestionScore(PID, EID ,QID, username ,score ,newScore) VALUES( '$_GET[PID]','$_GET[EID]' ,'$temp[$j]' , '$_SESSION[user]' , '-3' , '-3')";
    }else if($qscore<0){
      $sqlT = "INSERT INTO ExamQuestionScore(PID, EID ,QID, username ,score ,newScore) VALUES( '$_GET[PID]','$_GET[EID]' ,'$temp[$j]' , '$_SESSION[user]' , '0' , '0')";
      //echo "第".($j+1)."題<font color='red'>未通過</font><br>";
    }else if(strcmp($qscore,"1")==0){
      $sqlT = "INSERT INTO ExamQuestionScore(PID, EID ,QID, username ,score ,newScore) VALUES( '$_GET[PID]','$_GET[EID]' ,'$temp[$j]' , '$_SESSION[user]' , '$tempPaperScore[$j]' , '$tempPaperScore[$j]')";
      echo "第".($j+1)."題<font color='blue'>通過</font><br><br>------------------------------------------------------------------------------<br><br>";
      shell_exec("echo -e '第".($j+1)."題-通過\n' >>  /var/www/html/temp/".$_SESSION[user]."/exam/".$_GET[EID]."/grade_log.txt");
      $totaltemp+=$tempPaperScore[$j];
    }
    $conn2->query($sqlT);
    $miss=$miss || $qscore;
 
  }
  $totaltemp=round($totaltemp);
  //計算總成績寫入資料庫
  if($miss){
    if($determine){
    $sql = "INSERT INTO ExamScore(EID , username ,total) VALUES('$_GET[EID]' , '$_SESSION[user]' , '$totaltemp')";
    $result = $conn2->query($sql);
    echo "<br>結果：<font color='red'>成績待定</font>";
    $flush ='<script>parent.pB('.$totaltemp.');</script>';echo $flush;ob_flush();flush();
    }else{    
    $sql = "INSERT INTO ExamScore(EID , username ,total) VALUES('$_GET[EID]' , '$_SESSION[user]' , '$totaltemp')";
    $result = $conn2->query($sql);
    echo "<br>結果：$totaltemp 分";
    $flush ='<script>parent.pA('.$totaltemp.');</script>';echo $flush;ob_flush();flush();
    }
  }else{
    echo "<br>結果：缺考";
  }
  /*$sql = "select * from ExamQuestionScore where EID='$_GET[EID]' and username='$_SESSION[user]'";
  $result = $conn2->query($sql);
  if($result->num_rows>0){
    while($row = $result->fetch_assoc()){
      $totaltemp+=$row[score];
    }
  $sql = "INSERT INTO ExamScore(EID , username ,total) VALUES('$_GET[EID]' , '$_SESSION[user]' , '$totaltemp')";
  $result = $conn2->query($sql);
  echo "<br>結果：$totaltemp 分<br>------------------------------------------------------------------------------<br>";
}else{
  echo "<br>結果：缺考<br>------------------------------------------------------------------------------<br>";
}*/
//}
//$flush ='<script>parent.pA("'.$count.'");</script>';echo $flush;ob_flush();flush();
//$score=substr($score,0,-1);
/*$sql = "select * from ExamQuestionScore where EID='$_GET[EID]' and username='$_SESSION[user]'";
  $result = $conn2->query($sql);
  if($result->num_rows>0){
  while($row = $result->fetch_assoc()){
  $totaltemp+=$row[score];
  }
  }
  $sql = "INSERT INTO ExamScore(EID , username ,total) VALUES('$_GET[EID]' , '$_SESSION[user]' , '$totaltemp')";
  $result = $conn2->query($sql);
  echo "結果：$totaltemp 分";
  $flush ='<script>parent.pA("'.round($totaltemp,2).'");</script>';echo $flush;ob_flush();flush();
  $sql = "INSERT INTO ExamScore(EID , user, total)VALUES('$_GET[EID]' , '$_SESSION[user]' , '$scorestr')";
  $result = $conn2->query($sql);
 
//                                
//         echo $score;
//       echo $scorestr;*/
//          echo "<script>window.location.href = 'exameds.php?ID=$_GET[ID]';</script>";
}else{
  echo "<font color='red'>試卷已批改</font><br>";
}
$flush ='<script>parent.p("100");</script>';echo $flush;ob_flush();flush();
//  echo "<script>window.location.href = 'home.php';</script>";
?>
