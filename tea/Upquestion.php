<?php
  require("session.php");
  require("../mysql.php");
?>
<html>
  <script>
    function formSubmit(){
      document.getElementById("select").submit()
    }
  </script>
  <form id="select" method="post" action="questions.php?msg=ok&page=<?php echo $_GET[page]?>">
    <input type="hidden" name="OPT" id="OPT" value="ED">
    <input type="text" name="search" id="search" style="display:none" value="<?php echo $_GET[search]?>">
    <input type="text" name="selectSearch" id="selectSearch" style="display:none" value="<?php echo $_GET[selectSearch]?>">
    <input type="text" name="selectSearch1" id="selectSearch1" style="display:none" value="<?php echo $_GET[selectSearch1]?>">
    <input type="text" name="selectSearch2" id="selectSearch2" style="display:none" value="<?php echo $_GET[selectSearch2]?>">
<?php 
/*  if(strcmp($_POST[testmethod],"1")==0){//沒用到
    $str="InputAndCode";
  }else{
    $str="InputAndOutput";
  }*/
  $stack = array();
 
  $i=0;
  $count=0; 
  $name = str_replace("'","\'",$_POST[name]);
  $file=fopen('QuestionTest.txt','a+'); 
  for($i=0;$i<10;$i++){
    if(strcmp($_POST[testoutput.$i],"")!=0){
      $textTemp = str_replace("\r\n","\\\\n",$_POST[testoutput.$i]);  
      $text[$i] = str_replace("'","''",$textTemp);
      $text1Temp= str_replace("\r\n","\\\\n",$_POST[testinput.$i]);
      $text1[$i] = str_replace("'","''",$text1Temp);
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
      $scorestr[$i]=round($_POST[testscore.$i]/$x,2).","; 
    }
    $scorestr[$i]=round($_POST[testscore.$i]/$x,2);
  }else{
    for($i=0;$i<count($stack)-1;$i++){
      $scorestr[$i]=round(100/count($stack),2).",";
    }
    $scorestr[$i]=round(100/count($stack),2);
  }
  
  $testoutstr=substr($testoutstr,0,-1);
  $testinstr=substr($testinstr,0,-1);
  $sql = "select * from Question where QID='$_POST[ID]'";
  $result = $conn1->query($sql);
  if($result->num_rows>0){
    $row = $result->fetch_assoc();
    if(strcmp($row[founder],$_SESSION[user])==0){
    // junwu added for answer code
      $sql="update Question set name='$name',content='$_POST[textcontent]'
          ,isPublic='$_POST[selectauthority]',degree='$_POST[degree]' ,founder='$_SESSION[user]',tags='$_POST[tag]',code='$_POST[codetemp]',answerCode='$_POST[codetemp2]',proLan='$_POST[selectauthority1]',className='$_POST[className]' where QID='$_POST[ID]'";
      $result = $conn1->query($sql);
      	$sql = "delete from QuestionTest  where QID='$_POST[ID]'";
      	$conn1->query($sql);

      $sql = "UPDATE `QuestionTags` SET `mem_adr`='$_POST[isMemory]' where `QID`='$_POST[ID]'";
      $conn1->query($sql);

      for($i=0;$i<$count;$i++){
      	$score = $scorestr[$i];
      	$testinstr = $text1[$i];
      	$testoutstr = $text[$i];
      	$sql = "insert into QuestionTest (ID, QID, score, tIn, tOut) 
      	    values (NULL, '$_POST[ID]', '$score', '$testinstr', '$testoutstr')";
      	$conn1->query($sql);
	$str="QID=".$_POST[ID]." score=".$score." tIn=".$testinstr." tOut=".$testoutstr."\n";
      }
    }
  }
  fwrite($file,$str);
  fclose($file);
  //echo "<script>window.location.href = 'questions.php'</script>";
  echo "<script>formSubmit()</script>";
?>
  </form>
</html>

