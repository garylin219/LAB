<?php
	require("session.php");
  require("verifyMem.php");
	if($_SERVER['REQUEST_METHOD']=='POST'&&$_POST){
		try{
			$proLan=$_POST['proLan'];
			$className=$_POST['className'];
			$input=$_POST['input'];
      $isMemory=$_POST['isMemory'];
			$path="../../temp/".$_SESSION['user'];
			$codepath="cd ".$path.';';
			$file=fopen($path."/input.txt","w");
			fwrite($file,$input);
			fclose($file);
			//echo "輸入：<br>".str_replace("\n","<br>",$input)."<br>";
			
			shell_exec($codepath."echo ' ' >output.txt");
			if(strcmp($proLan,"JAVA")==0){
				$output=shell_exec($codepath.'sudo javac -encoding utf-8 '.$className.'.java 2>&1 1> /dev/null');
			}
			else if(strcmp($proLan,"CPP")==0){
				$output=shell_exec($codepath.'sudo g++ code.cpp 2>&1 1> /dev/null');
			}
			else{
        if($isMemory == 1){
          shell_exec('sudo cp ../c_head/verifyMem.h '.$path);
          shell_exec("sudo chown -R apache:apache ".$path); 
          $codeTemp = file_get_contents($path."/code.c");
          $code_file = fopen($path."/code.c","w");
          $codeTemp = putVerifyContent($codeTemp);//func putVerifyContent by verifyMem.php
          fwrite($code_file,$codeTemp);
          fclose($code_file);
        }
				$output=shell_exec($codepath.'sudo gcc code.c 2>&1 1> /dev/null');
			}
			if(empty($output)){
				$S1=microtime(true);
				if(strcmp($proLan,"JAVA")==0){
					$outputRun=shell_exec($codepath."timeout 3s java -Dfile.encoding=UTF-8 ".$className." < input.txt 2>&1 1> /dev/null");//java -Dfile.encoding=UTF-8 
				}else{
					$outputRun=shell_exec($codepath."timeout 3s ./a.out < input.txt 2>&1 1> /dev/null");//java -Dfile.encoding=UTF-8
				}
				$S2=microtime(true);
				if(empty($outputRun)){
					$S1=microtime(true);
					if(strcmp($proLan,"JAVA")==0){
						shell_exec($codepath."timeout 3s java -Dfile.encoding=UTF-8 ".$className." < input.txt > output.txt");//java -Dfile.encoding=UTF-8
					}else{
						shell_exec($codepath."timeout 3s ./a.out < input.txt > output.txt");//java -Dfile.encoding=UTF-8
            if($isMemory == 1){

              $codeTemp = file_get_contents($path."/code.c");
              $code_file = fopen($path."/code.c","w");
              $codeTemp = removeVerifyContent($codeTemp);//func removeVerifyContent by verifyMem.php
              fwrite($code_file,$codeTemp);
              fclose($code_file);
            }
					}
					$S2=microtime(true);
					$txtSize = shell_exec($codepath.'du output.txt');
					$txtSize = str_replace("	output.txt","",$txtSize);
					if(($txtSize<100)&&(($S2-$S1)<3)){
						$output = shell_exec($codepath.'cat output.txt');
						$output = str_replace(" ","&nbsp;",$output);
					}else{
						$output = "<font color='red'>無限迴圈或演算法過長</font><br>";
					}
					//$output = str_replace("\t","tab",$output);
					//$output = str_replace("\n","<img src='../upimages/enterImg.png' width='20' height='20' /><br>",$output);
				}else{
					$output=$outputRun;
				}
			}
			//$output = str_replace("\n","<br>",$output);
//			echo "<br>你的輸出：<br>".$output;
			echo $output;
			/*
			echo "<br>系統判定：";

			if($txtSize<100){
				$output = shell_exec($codepath.'sudo diff output.txt correctoutput.txt');
				if(empty($output)){
					echo "<font color='#0000FF'>通過</font>";
				}else{
					echo "<font color='#FF0000'>未通過</font>";
				}
			}else{ 
				echo "<font color='#FF0000'>未通過</font>";
			}
			echo "(執行時間：".round($S2-$S1,4)."秒)";
			 */
		}catch (Exception $e){
			echo $e;
		}
	}
	
?>
