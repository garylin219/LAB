<?php
  require("session.php");
  require("../mysql.php");  
  /*if(strcmp($_POST[testmethod],"1")==0){
    $str="InputAndCode";
  }else{
    $str="InputAndOutput";
  }*/
  $stack = array();
 
  $i=0;
  $count=0;
  $name = str_replace("'","\'",$_POST[name]);
  for($i=0;$i<10;$i++){
    if(strcmp($_POST[testoutput.$i],"")!=0){
      $text[$i] = str_replace("\r\n","\\\\n",$_POST[testoutput.$i]);
      $text1[$i] = str_replace("\r\n","\\\\n",$_POST[testinput.$i]);
      //$testoutstr.=$text."\n";
      //$testinstr.=$text1."\n";
      $sum+=$_POST[testscore.$i];
      array_push($stack,$_POST[testscore.$i]);
      $count++;
    }
  }
  if($sum!=0){
    $x=$sum/100;
    for($i=0;$i<count($stack)-1;$i++){
      $scorestr[$i]=round($_POST[testscore.$i]/$x,2); 
    }
    $scorestr[$i]=round($_POST[testscore.$i]/$x,2);
  }else{
    for($i=0;$i<count($stack)-1;$i++){
      $scorestr[$i]=round(100/count($stack),2);
    }
    $scorestr[$i]=round(100/count($stack),2);
  }
  
  $testoutstr=substr($testoutstr,0,-1);
  $testinstr=substr($testinstr,0,-1);
  $sql = "insert into Question (QID, name, content, isPublic, degree, founder, tags, code, answerCode, proLan, className) 
        values (NULL, '$name', '$_POST[textcontent]', '$_POST[selectauthority]', '$_POST[degree]', '$_SESSION[user]', '$_POST[tag]','$_POST[codetemp]','$_POST[codetemp2]','$_POST[selectauthority1]','$_POST[className]')";
  $result = $conn1->query($sql);
  if(!is_dir("../temp")){
    shell_exec("sudo mkdir ../temp/");
  }

  $sql = "SELECT LAST_INSERT_ID() as QID";
  $result = $conn1->query($sql);
  $data = $result->fetch_assoc();

  $sql = "INSERT INTO `QuestionTags`(`ID`, `QID`, `mem_adr`) VALUES (NULL,'$data[QID]','$_POST[isMemory]')";
  $conn1->query($sql);

  for($i=0;$i<$count;$i++){
    $score = $scorestr[$i];
    $testinstr = $text1[$i];
    $testoutstr = $text[$i];
    $sql = "insert into QuestionTest (ID, QID, score, tIn, tOut) 
          values (NULL, '$data[QID]', '$score', '$testinstr', '$testoutstr')";
//    echo $data[QID].",".$score.",".$testinstr.",".$testoutstr."<br>";
    $result = $conn1->query($sql);
  }
//  echo "$_POST[codetemp]";
  echo "<script>window.location.href = 'questions.php?msg=ok&OPT=AD'</script>";


?>
