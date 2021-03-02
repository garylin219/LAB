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
    <input type="hidden" name="OPT" id="OPT" value="<?php echo $_POST[OPT]?>">
    <input type="text" name="search" id="search" style="display:none" value="<?php echo $_GET[search]?>">
    <input type="text" name="selectSearch" id="selectSearch" style="display:none" value="<?php echo $_GET[selectSearch]?>">
    <input type="text" name="selectSearch1" id="selectSearch1" style="display:none" value="<?php echo $_GET[selectSearch1]?>">
    <input type="text" name="selectSearch2" id="selectSearch2" style="display:none" value="<?php echo $_GET[selectSearch2]?>">
      <?php
	$success=false;
	
	// echo $_POST[OPT];
	
	if($_POST[OPT]=="RM")
	{
          if($_POST[CHE]){
            $str = implode(",", $_POST[CHE]);
            $a =explode(",",$str);
            for($i=0;$i<count($a);$i++){
              $sql = "delete from Question where QID='$a[$i]' AND founder='$_SESSION[user]'";
              if($conn1->query($sql)){
    	        $sql = "delete from QuestionTest where QID='$a[$i]'";
    	        $conn1->query($sql);
    	        //echo "<script>window.location.href = 'questions.php?msg=ok'</script>";
              $sql = "DELETE FROM `QuestionTags` WHERE `QID`='$a[$i]'";
              $conn1->query($sql);
    	        $success=true;
              }
            }
          }
 
          if($success){
	    echo "<script>formSubmit()</script>";
          }else{
            echo "<script>window.location.href = 'questions.php'</script>";
          }
        }
        else if($_POST[OPT]=="CP")
        {
//          echo "copying";
          $str = implode(",", $_POST[CHE]);
          $a =explode(",",$str);
  //        echo $a[0];
          $sql = "insert into Question (name, content, isPublic, degree, tags, code, answerCode, proLan, className, founder) select CONCAT(name,'(複製)'), content, isPublic, degree, tags, code, answerCode, proLan, className,'$_SESSION[user]' from Question where QID='$a[0]'";          
  //        echo "<br>";
  //        echo $sql;
 //         echo "<br>";
          if($conn1->query($sql))
          {
            $sql = "SELECT `mem_adr` FROM `QuestionTags` WHERE `QID`='$a[0]'";
            $result = $conn1->query($sql);
            $mem = $result->fetch_assoc();
          
            /*if(!is_dir("../temp")){
              shell_exec("sudo mkdir ../temp/");
            }*/

            $sql = "SELECT LAST_INSERT_ID() as QID";
            $result = $conn1->query($sql);
            $data = $result->fetch_assoc();

            

            $sql = "INSERT INTO `QuestionTags`(`ID`, `QID`, `mem_adr`) VALUES (NULL,'$data[QID]','$mem[mem_adr]')";
            $conn1->query($sql);

            $sql2 = "select * from QuestionTest where QID='$a[0]'";
            $result = $conn1->query($sql2);
            $i=0;
            

            while($aQT=$result->fetch_assoc())
            {
            
              $score = $aQT[score];
              $testinstr = str_replace("\\n","\\\\n", $aQT[tIn]);
              $testoutstr =str_replace("\\n","\\\\n", $aQT[tOut]);
              
              $sql = "insert into QuestionTest (QID, score, tIn, tOut) values ('$data[QID]', '$score', '$testinstr', '$testoutstr')";
           //   echo $sql;
           //   echo "<br>"; 
              $conn1->query($sql);
              $i++;
            } 
            
            $success=true;
          }
          
          if($success){
            echo "<script>formSubmit()</script>";
          }else{
            echo "<script>window.location.href = 'questions.php'</script>";
          }
          

        }

?>
  </form>
</html>
