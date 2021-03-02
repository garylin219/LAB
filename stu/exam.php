<?php
	session_start();
	require("session.php");
	require("../mysql.php");
	 $sql = "select * from PapersQuestion,PapersScore where PapersQuestion.PID='$_GET[PID]' AND PapersQuestion.QID='$_GET[QID]' AND PapersScore.PID=PapersQuestion.PID AND PapersScore.QID=PapersQuestion.QID";
         $result = $conn2->query($sql);
         $proLan="";
         $row = $result->fetch_assoc();
         $proLan=$row[proLan];
?>
<html>
	<head>
		<title>Smart Exam</title>
                <meta name="google" content="notranslate" />
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
	       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>	
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="../assets/css/main.css">
		<link rel="stylesheet" href="../cssmenu/styles.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="../assets/js/jquery-latest.min.js" type="text/javascript"></script>
		<script src="../cssmenu/script.js"></script>
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="../sweetalert/sweetalert-dev.js"></script>
                <link rel="stylesheet" href="../sweetalert/sweetalert.css">
		<link rel="stylesheet" type="text/css" href="../ace-se-extends/editor-extends.css">
		<style type="text/css" media="screen">
			#output{
				padding-top:15px;
				padding-left:10px; 
				padding-right:30px; 
				margin: 0;
				position: absolute;
				top: 61%;
				bottom: 0;
				left: 31%;
				height: 39%;
				right: 0;
				overflow-x:auto;
				overflow-y:auto;
			}
			#editor {
				margin: 0;
				position: absolute;
				top: 8%;
				bottom: 0;
				left: 30%;
				height: 52%;
				right: 0;
			}/*
			#wetty{
				margin: 0;
				position: absolute;
				top: 60%;
				left: 30%;
				height: 40%;
				width:70%;
			}*/
			#input{
				margin: 0;
				position: absolute;
				padding-top:15px; 
				padding-left: 20px; 
				padding-right: 20px;
				top: 8%;
				bottom: 0;
				left: 0%;
				right: 0;
				height: 92%;
				width: 30%;
				overflow-x:auto;
				overflow-y:auto;
				background-color:#ffffff; 
				z-index:102;
				font-family: monospace; 
			}
			#hrdiv{
				position: absolute;
				top: 60%;
				bottom: 0;
				left: 0%;
				right: 0;
				width: 100%;
				height: 3pt;
				overflow-x:hidden;
				overflow-y:auto;
				background-color:#c0c0c0;
				z-index:101;
			}
			#hrdiv1{
				position: absolute;
				top: 8%;
				bottom: 0;
				left: 30%; 
				right: 0; 
				width: 3pt;
				background-color:#c0c0c0;
				z-index:100;
			}
			@media screen and (max-width: 768px) {
				#headerA{ 
					position: absolute;
					top: 0%;
					left: 0%;
					display: block;
					background-color:#2e3842;
				}
				#header,#Nav{
					display: none;
					overflow:scroll;
				}
			}
		</style>
		<script>
			var END=0;
		</script>
		<script type="module">
			import * as sedate from '../se/js/date.js';
			var intervalId;
			var endTime = new Date(
			<?php
				$sql = "select * from Exams where EID='$_GET[EID]'";
				$result = $conn2->query($sql);
				if($result->num_rows>0){
					$row = $result->fetch_assoc();
					echo "'$row[examEndTime]'";
				}
			?>);
			function execFunc() {
				var currentTime = sedate.getDateTime();
				if(((endTime.getTime() - currentTime.getTime()) / 1000) < 1) {
					sedate.stopDisplay(intervalId);
					END=1;
					Timeout();
					window.onbeforeunload =false;
					inlog("execFunc");
					window.location.href ='Loading2.php?EID=<?php echo "$_GET[EID]";?>&PID=<?php echo "$_GET[PID]";?>&RD=<?php echo "$_GET[RD]";?>';
				}	
				if(ReplyJ==1&&escT==0){
					document.getElementById("IframeC").src="GetLog.php?EID=<?php echo "$_GET[EID]";?>";
					X=$('#IframeC').contents().find('#box').html();
					if(X==1){
						ReplyJ=0;
						swal.close();
					}
				}
			}
			window.addEventListener('load', function() {
				intervalId=sedate.displayCountdownTime('showbox', endTime, execFunc);
			});
		</script>
		<script>
		        var selectVal=-1;
        		function changed(theselect) {
			inlog("切換測試檔");
	        	        if(theselect.value!="end"){
	        	                /*
	        	                tempstr1=testOut[theselect.value].replace(/\n/g,"<font color='#FF0000'><換行符號></font><br>");
	        	                document.getElementById("correctoutput").innerHTML="參考輸出：<br>"+tempstr1;
	        	                document.getElementById("userout").innerHTML="";
	        	                
	        	                testeditor.setValue(testIn[theselect.value]);
	        	                testeditor.setReadOnly(true);*/
	        	                document.getElementById("testinput").style.display="none";
	        	                selectVal=theselect.value;
                                }else{
                                        document.getElementById("testinput").focus();
                                        document.getElementById("testinput").style.display="block";
                                        selectVal=-1;
                                }
                        }
                        function changed1(theselect) {
			inlog("切換題目");
                                window.onbeforeunload =false;
                                window.location.href = 'exam.php?EID=<?php echo "$_GET[EID]";?>&PID=<?php echo "$_GET[PID]";?>&QID='+theselect.value+'&qnum='+theselect.selectedIndex+'&re=0&RD=<?php echo "$_GET[RD]";?>';
                        }
                        function prevQ(){
			inlog("上一頁");
                                y=parseInt(<?php echo "0$_GET[qnum]";?>);
                                if(0 < y){
                                        y--;
                                        window.onbeforeunload =false;
                                        window.location.href = 'exam.php?EID=<?php echo "$_GET[EID]";?>&PID=<?php echo "$_GET[PID]";?>&QID='+document.getElementById("selectSearch1")[y].value+'&qnum='+y+'&re=0&RD=<?php echo "$_GET[RD]";?>';
                                }else{
                                        swal("已經是第一題了!");
                                }
                        }
                        function nextQ(x){
			inlog("下一頁");
                                y=parseInt(<?php echo "0$_GET[qnum]";?>);
                                if(x > y){
                                        y++;
                                        window.onbeforeunload =false;
                                        window.location.href = 'exam.php?EID=<?php echo "$_GET[EID]";?>&PID=<?php echo "$_GET[PID]";?>&QID='+document.getElementById("selectSearch1")[y].value+'&qnum='+y+'&re=0&RD=<?php echo "$_GET[RD]";?>';
                                }else{
                                        swal("已經是最後一題了!");
                                }
                        }
		</script>
		<script>
		        function listFunction(){
		                if(document.getElementById("header").style.display=="block"){
	        	                document.getElementById("header").style.display="none";
                		        document.getElementById("header").style.top="0%";
                		        document.getElementById("header").style.height="8%";
	        	        }else{
        		                document.getElementById("header").style.display="block";
		                        document.getElementById("header").style.top="8%";
	        	                document.getElementById("header").style.height="92%";
        		        }
		        
		        }
		        function recode(){
			inlog("還原");
	        	        swal({
        	        	        title: "確定要還原成預設程式碼?",
		                        showCancelButton: true,
		                        closeOnConfirm: false,
        		        },
        		        function(inputValue){
	                	        if(inputValue === false){
        	        	                return false;
        		                }else{
						//editor.setValue(tmpl1);
						se_extends.setValue(editor, tmpl1);
        		                        swal.close();
        		                }
                                });
        		        return false;
		        }
			$(document).ready(function() {
		        $("#runbtn").click(function() {
			inlog("執行");
			$("#result").hide();
			$("#loadingImg").show();
		                if(END==0){
        	        	        var tempstr;
					//tempstr=editor.getValue();//.replace(/\r\n|\n/g,"<br>");
					tempstr=se_extends.getValue(editor);
        		                //tempstr=tempstr.replace(/\\/g,"\\\\");
        		                if(tempstr.match('fopen')!=null){
                		                swal("禁止使用fopen");
                		                return false;
        		                }
		                        var a="";
	        	                tempstr=tempstr.replace(/(\/)/g,"<斜線>");
        		                tempstr=tempstr.replace(/\\/g,"\\\\");
		                        tempstr=tempstr.replace(/\'/g,"''");
				        var codestr=tempstr;		
		                        document.getElementById("codetemp").innerHTML=tempstr;
	        	                if(selectVal!=-1){
        		                        tempstr=testIn[selectVal];
		                        }else{
                                                tempstr=document.getElementById("testinput").value;
        		                }
		                        var inputstr=tempstr;
		                        document.getElementById("inputtemp").innerHTML=tempstr;
					var selectstr= document.getElementById("selectSearch").value;
          var isMem = document.getElementById("isMemoryTemp").value;
					runck="1";
    $.ajax({
      type: "POST", 
      url: "service.php",
      dataType: "json", 
      data: { 
        codetemp:codestr,
        inputtemp:inputstr,
  	    EID:"<?php echo $_GET[EID];?>",
  	    QID:"<?php echo $_GET[QID];?>",
        PID:"<?php echo $_GET[PID];?>",
        isMem:isMem,
        selectSearch:selectstr,
	      proLan:"<?php echo $proLan;?>",
      },
        success: function(data) {
          if (data.codetemp) { 
      	    $("#loadingImg").hide();
      	    $("#result").show();
            $("#result").html(data.codetemp);
          } else { 
            $("#result").html(data.errorMsg);
          }
        },
          error: function(jqXHR) {
            console.log("fail")
            $("#result").html('<font color="#ff0000">發生錯誤：' + jqXHR.status + '</font>');
          }
    })
 
	        	                // window.onbeforeunload =false;
        		                //document.getElementById('examform').submit();
			}		           
		        })
});
		</script>
		<script>
		        /*wetty focus*//*
		        function checkFocus() {
        		        if(document.activeElement == document.getElementsByTagName("iframe")[0]) {
//	                	        console.log('iframe has focus');
	                	        return true;
        		        } else {
//		                        console.log('iframe not focused');
		                        return false;
		                }
		        }*/
		                                                                
		</script>
		<script>
		        document.onkeydown = function(){
        		        switch (event.keyCode){
        		                case 114 : //F3 button
        		                        event.returnValue = false;
        		                        event.keyCode = 0;
        		                        return false;
        		                                                                                                                        
                		        case 116 : //F5 button
                        		        event.returnValue = false;
                        		        event.keyCode = 0;
                        		        return false;
                		        case 82 : //R button
                        		        if (event.ctrlKey){ 
                                		        event.returnValue = false;
                                		        event.keyCode = 0;
                                		        return false;
                        		        }
                                        case 83 : //S button
                                                if (event.ctrlKey){ 
                                                        event.returnValue = false;
                                                        event.keyCode = 0;
                                                        return false;
                                                
                                                }
                                        case 69 : //E button
                                                if (event.ctrlKey){ 
                                                        event.returnValue = false;
                                                        event.keyCode = 0;
                                                        return false;
                                        
                                                }
                                        case 79 : //O button
                                                if (event.ctrlKey){ 
                                                        event.returnValue = false;
                                                        event.keyCode = 0;
                                                        return false;
                                                
                                                }
                                        case 71 : //G button
                                                if (event.ctrlKey){ 
                                                        event.returnValue = false;
                                                        event.keyCode = 0;
                                                        return false;
                                                }
                                        case 74 : //S button
                                                if (event.ctrlKey){ 
                                                        event.returnValue = false;
                                                        event.keyCode = 0;
                                                        return false;
                                        
                                                }
                                        case 68 : //D button
                                                if (event.ctrlKey){ 
                                                        event.returnValue = false;
                                                        event.keyCode = 0;
                                                        return false;
                                                }
                                        case 76 :
                                                if (event.ctrlKey){ 
                                                        event.returnValue = false;
                                                        event.keyCode = 0;
                                                        return false;
                                                }
                                        case 84 :
                                                if (event.ctrlKey){
                                                        event.returnValue = false;
                                                        event.keyCode = 0;
                                                        return false;
                                                }
        		        }
		        }
		</script>
		<script>
		        
		        var hrD,hr1D,REPLY=0;
		        document.onmousemove = getMouseXY;
		        document.onmouseup = resethr;
		        function hrdown(e){
		                hrD=1;
		                if((e.pageX>document.getElementById("hrdiv1").offsetLeft-3) && (e.pageX<document.getElementById("hrdiv1").offsetLeft+3)){
		                        hr1D=1;
		                }
		                tlock();
		        }
		        function getMouseXY(e){
        		        if(e.pageY<(document.body.clientHeight*0.9) && e.pageY>(document.body.clientHeight*0.20)){
	        	                if(hrD==1){
		                                document.getElementById("hrdiv").style.top=e.pageY-1;
        		                        document.getElementById("output").style.top=e.pageY;
	        	                        document.getElementById("output").style.height=(document.body.clientHeight-e.pageY-1);
		                                //document.getElementById("testeditor").style.top=e.pageY+2;
		                                //document.getElementById("testeditor").style.height=(document.body.clientHeight-e.pageY-2);  
		                                //document.getElementById("input").style.height=(e.pageY-document.getElementById("input").offsetTop-1);
        		                        document.getElementById("editor").style.height=(e.pageY-document.getElementById("editor").offsetTop-1);
	        	                        editor.resize();
		                                //testeditor.resize();
        		                }
        	                }
                                if(e.pageX<(document.body.clientWidth*0.9) && e.pageX>(document.body.clientWidth*0.1)){
	        	                if(hr1D==1){
        		                        document.getElementById("hrdiv1").style.left=e.pageX-1;
                                                document.getElementById("input").style.width=e.pageX-1;
                                                //document.getElementById("testeditor").style.width=e.pageX-1;
                                                document.getElementById("editor").style.left=e.pageX+1;
                                                document.getElementById("output").style.left=e.pageX+10;
        		                        editor.resize();
	        	                        //testeditor.resize();
		                        }
                                }
		                
		                if((e.pageX>document.getElementById("hrdiv1").offsetLeft-1) && (e.pageX<document.getElementById("hrdiv1").offsetLeft+3)){
		                        document.getElementById("hrdiv").style.cursor="move";
		                }else{
		                        document.getElementById("hrdiv").style.cursor="n-resize";
		                }
		                                                                                        
		        }
		        function hrup(e){
		                resethr();
		        }
		        
		        
		        function hr1down(e){
		                hr1D=1;
		                tlock();
		        }
		        function hr1up(e){
		                resethr();
		        }
                        function resethr(){
		                hrD=0;
		                hr1D=0;
		                ulock();
		        }
		        function tlock(){ window.document.onselectstart = dde; }/*拖曳的時候會反白到 所以禁止反白*/
		        function ulock(){ window.document.onselectstart = ddt; }
		        function ddt(){ return true; }
		        function dde(){ return false; }
		</script>
		<script>
		        function end(){
			inlog("提前結束");
		                if(END==0){
		                        swal({
		                        	title: "確定要提交試卷，一旦提交就無法再修改?",
                                        	text: '確認試卷內每題都作答完畢,請輸入ok',
                                        	type: 'input',
                                        	animation: "slide-from-top", 
                                        	inputPlaceholder: '請輸入ok',
                                        	showCancelButton: true,
                                        	closeOnConfirm: false,
					},
	        	                        function(inputValue){ 
                                  		if (inputValue === false) return false; 
                                 		if (inputValue === "") { 
                                    			swal.showInputError("提交試卷請輸入ok");
                                    			return false }
                                  		if (inputValue != "ok"){
                                        		swal.showInputError("提交試卷請輸入ok");
                                        		return false;
						}else{
        		                                window.onbeforeunload =false;
//		                                window.location.href ='Endexam.php?ID=<?php echo "$_GET[ID]";?>';
		                                        window.location.href ='Loading2.php?EID=<?php echo "$_GET[EID]";?>&PID=<?php echo "$_GET[PID]";?>&RD=<?php echo "$_GET[RD]";?>';
                		                }
        		                });
		                }
		                return false;
		        }
        		var escT=0;//判斷blur是不是因為要關閉視窗才blur的
        		var escCount=0,X;
        		NUM=0;
        		var STR="你離開了考試!";
		        window.onbeforeunload = function(event) {
		                escT=1;
		                ReplyJ=0;
//document.getElementById("Iframe").src="Log.php?ID=<?php echo "$_GET[ID]";?>&N=6";
		                event.returnValue = "確定要結束考試?";
                        };
	               	window.onblur = function() {
	               	        console.log('blur');
	               	
                                /*if(checkFocus()){
                                        console.log('no');
                                }else{
                                        addFunction();
                                        console.log('blur');
                                }*/

                                if(escT==0){
                                        document.getElementById("Iframe").src="Log.php?EID=<?php echo "$_GET[EID]";?>&N=1";
					addFunction();
                                }else{
                                        escCount++;
//                                        document.getElementById("Iframe").src="Log.php?ID=<?php echo "$_GET[ID]";?>&N=5";
                                        if(escCount>0){
                                                document.getElementById("Iframe").src="Log.php?EID=<?php echo "$_GET[EID]";?>&N=6";
                                                STR="嘗試關閉考試!";
                                                addFunction();
                                        }
                                        escT=0;
                                }
        		};
        		
        		
                	window.onfocus = function() {
                	//        document.getElementById("Iframe").src="Log.php?ID=<?php echo "$_GET[ID]";?>&N=4";
                		console.log('focus');
                                
        		};
                        var ReplyJ=0;
        		function ReplyFunction(){
        		        ReplyJ=1;
        		        swal({
                		        title: "等待確認",
                		        text: "請監考人員點選確認回復考試\n\n",
        	        	        type: "info",
        		                showCancelButton: false,
        		                closeOnConfirm: false,
                		        allowEscapeKey: false,
                		        confirmButtonText: "完成",
        	        	        },
        	                function(){
        	                        document.getElementById("IframeC").src="GetLog.php?EID=<?php echo "$_GET[EID]";?>";
        	                        X=$('#IframeC').contents().find('#box').html();
        	                        if(X==1){
        	                                ReplyJ=0;
        	                                document.getElementById("examform").action ="exam.php?EID=<?php echo "$_GET[EID]";?>&PID=<?php echo "$_GET[PID]";?>&QID=<?php echo "$_GET[QID]";?>&qnum=<?php echo "$_GET[qnum]";?>&re=0&RD=<?php echo "$_GET[RD]";?>";
        	                                swal.close();
                                        }else{
                                                ReplyFunction();
        	                        }
               		        });
        		}
        		function Timeout(){
        		        swal({
        	        	        title: "時間已到",
                		        text: "等待成績計算，請勿任意操作功能。\n\n",
                		        type: "warning",
        		                showConfirmButton: false,
                		        allowEscapeKey: false,
                		        timer: 9999999,
                                });
        		}
        		function addFunction() {
        		        var NowDate=new Date();
				<?php $spl = "select * from Exams where EID='$_GET[EID]'";
				$result = $conn2->query($sql);
                                if($result->num_rows>0){
				$row = $result->fetch_assoc();
				}
				if($row[exammode]=='0'){
				?>	
//       		        document.getElementById("Iframe").src="Log.php?ID=<?php echo "$_GET[ID]";?>&N=1";
                		swal({
                        		title: STR,
                        		text: "請與監考人員取得復原密碼\n("+NowDate.toLocaleString()+")",
                	        	type: "input",
                        		inputType: "password",
                        		showCancelButton: false, 
                        		closeOnConfirm: false,
                        		allowEscapeKey: false,
        	                	animation: "slide-from-top",
                		},
                        		function(inputValue){
                                		if(inputValue === false) return false;
                                		if(inputValue === "") {
                                		        document.getElementById("Iframe").src="Log.php?EID=<?php echo "$_GET[EID]";?>&N=3";
                                        		swal.showInputError("請輸入密碼!");
                                        		return false;
                                		}else if(inputValue ===<?php
                                		$sql = "select * from Exams where EID='$_GET[EID]'";
                                		$result = $conn2->query($sql);
                                		if($result->num_rows>0){
                                        		$row = $result->fetch_assoc();
                                        		echo "'$row[password]'";
                                		}else{echo "''";}?>){
                                		        document.getElementById("Iframe").src="Log.php?EID=<?php echo "$_GET[EID]";?>&N=2";
                                		        document.getElementById("IframeC").src="GetLog.php?EID=<?php echo "$_GET[EID]";?>";
                                		        X=$('#IframeC').contents().find('#box').html();
                                		        ReplyFunction();
//                                		        swal.close();
                                		}else{
                                		        document.getElementById("Iframe").src="Log.php?EID=<?php echo "$_GET[EID]";?>&N=3";
                                		        swal.showInputError("錯誤!");
                                		        return false;
                                		}
                		});<?php } ?>
                		STR="你離開了考試!";
        		};
                        function runRD(){
                                <?php
                                        if((strcmp("start",$_GET[start])==0)&&(strcmp($_GET[re],"1")!=0)){
						echo '
							swal({
                                  				title: "↑這是空格符號↑",
								text: "請依照題目要求的格式輸出，格式不同即為錯誤。\n\n",
                                                                imageUrl: "../upimages/spaceImg.png",
                                 		   		OK: true,
                                        			closeOnConfirm: false,
                                			},
                                			function(inputValue){
                                        			if(inputValue === false){
                                                			return false;
                                        			}else{
									swal({
                                                                                title: "↑這是換行符號↑",
                                                                                text: "請依照題目要求的格式輸出，格式不同即為錯誤。\n\n",
                                                                                imageUrl: "../upimages/enterImg.png",
                                                                        });
                                        			}
                                			});
						';
                                        }
                                        $sql = "select * from ExamLog where status='開始' and username='$_SESSION[user]' and EID='$_GET[EID]'";
                                        $result = $conn2->query($sql);
                                        if($result->num_rows>0){
                                                $row = $result->fetch_assoc();
                                                if(strcmp($row[RD],$_GET[RD])!=0){
                                                        echo "document.getElementById('Iframe').src='Log.php?EID=$_GET[EID]&N=4';
                                                        window.onbeforeunload =false;
                                                        window.location.href = 'error.php';
                                                        ";
                                                        
                                                }else{
                                                        $sql = "select * from ExamLog where status='結束' and username='$_SESSION[user]' and EID='$_GET[EID]'";
                                                        $result = $conn2->query($sql);
                                                        if($result->num_rows>0){
                                                                echo "window.onbeforeunload =false;
                                                                window.location.href = 'error.php';";
                                                                                                                
                                                        }
                                                }
                                        }else{
                                        
                                                echo "window.onbeforeunload =false;
                                                window.location.href = 'error.php';";
                                        }
                                        $sqlC = "select * from ExamLog where EID='$_GET[EID]' AND username='$_SESSION[user]' order by time desc limit 1";
                                        $resultC = $conn2->query($sqlC);
                                        $rowC = $resultC->fetch_assoc();
                                        if(strcmp("回復",$rowC[status])==0){
                                                echo "ReplyFunction();";
                                        }
                                        if(strcmp($_GET[re],"1")==0){
                                                echo "document.getElementById('Iframe').src='Log.php?EID=$_GET[EID]&N=1';
                                                addFunction();";
                                                
                                        }
                                        $sqlEX = "select * from Exams where EID ='$_GET[EID]'";
                                        $resultEX = $conn2->query($sqlEX);
                                        $rowEX = $resultEX->fetch_assoc();
                                        //$pID=$rowEX[PID];
                                        $sqlP = "select * from PapersQuestion where PID ='$rowEX[PID]'";
                                        $resultP = $conn2->query($sqlP);
                                        //$rowP = $resultP->fetch_assoc();
                                        //$temp=explode(",",$rowP[questionID]);
                                        $JJ=0;
					if($resultP->num_rows>0){
		                                while($rowP = $resultP->fetch_assoc()){

                                        //for($i=0;$i<count($temp)&&($JJ==0);$i++){
        	                                        if($rowP[QID]==$_GET[QID]){
                                                        $JJ=1;
                	                                }
                        	                }
					}
                                        if($JJ==0){
                                                echo "window.location.href='exams.php?msg=error'";
                                        }
                                ?>
                         };
		</script>
	</head>
	<body onload="runRD();checksave();" style="background-color:#ffffff">
		<form id="examform" method="post" action="exam.php?EID=<?php echo "$_GET[EID]";?>&PID=<?php echo "$_GET[PID]";?>&QID=<?php echo "$_GET[QID]";?>&qnum=<?php echo "$_GET[qnum]";?>&RD=<?php echo "$_GET[RD]";?>"> 
		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header" style="height:8%">
						<ul class="actions">
							<li></li>
							<li><a class="icon fa-arrow-left" href="javascript:;" onclick="prevQ();">上一題</a></li>
							<li>
								<select name="selectSearch1" id="selectSearch1" onChange="changed1(this)" style="background-color:#7e8892;font-size:13px;">
<!--									<option value="ID" <?php if(strcmp($_POST[selectSearch1],"ID")==0)echo "selected";?>>題目列表</option>-->
									<?php
									        $paperScoretemp=array();
									        $sql = "select * from PapersScore where PID='$_GET[PID]' order by queue";
									        $result = $conn2->query($sql);
										$i=0;
									        if($result->num_rows>0){
									                while($row = $result->fetch_assoc()){
									                        //$questionID=explode(",",$row[questionID]);
									                        //$testopentemp=explode(",",$row[testopen]);
									                        //$paperScoretemp=explode(",",$row[score]);
									                        //for($i=0;$i<count($questionID);$i++){
        								                                
													if($_GET[QID]==$row[QID]){
	        								                                if(strcmp($row[testOpen],"1")==0){
		        							                                        $testopenY=1;
			        						                                }
									                                        echo "<option value='$row[QID]' selected>第$row[queue]題($row[score]%)</option>";
														$i++;
									                                }else{
        									                                echo "<option value='$row[QID]'>第$row[queue]題($row[score]%)</option>";
														$i++;
									                                }
									                        //}
									                }
									        }
									        $totalQ=$i-1;
									?>
								</select>
							</li>
							<li><a href="javascript:;" onclick="nextQ(<?php echo "$totalQ";?>);" class="icon">下一題<i class="icon fa-arrow-right"></i></a></li>
							<li></li><li></li>
							<li>
								<select name="selectSearch" id="selectSearch" onChange="changed(this)" style="background-color:#7e8892;font-size:13px;">
        							        <?php
        							        if(strcmp($testopenY,"1")==0){
								                $sql = "select * from PapersQuestionTest,PapersQuestion where PapersQuestionTest.ID=PapersQuestion.ID and PID='$_GET[PID]' AND QID='$_GET[QID]'";
								                $result = $conn2->query($sql);
								                if($result->num_rows>0){
											$i=0;
        								                while($row = $result->fetch_assoc()){
                                                                                                //$testOutput=explode("\n",$row[testOutput]);
                                                                                                //$testInput=explode("\n",$row[testInput]);
                                                                                                //$testScoretemp=explode(",",$row[score]);
                                                                                                //for($i=0;$i<count($testInput);$i++){
                                                                                                        if(strcmp($_POST[selectSearch],"$i")!=0){
                                                                                                                echo "
                                                                                                                        <option value='$i'>測試檔". ($i+1) ."</option>
                                                                                                                ";
                                                                                                        }else{
                                                                                                                echo "
                                                                                                                        <option value='$i' selected>測試檔". ($i+1) ."</option>
                                                                                                                        <script>selectVal=$i;</script>
                                                                                                                ";
                                                                                                        }
                                                                                                //}
                                                                                                if(strcmp($_POST[selectSearch],"end")==0){
                                                                                                        echo "<script>selectVal=-1;</script>";
                                                                                                }else if(strcmp($_POST[selectSearch],"")==0){
                                                                                                        echo "<script>selectVal=0;</script>";
                                                                                                }
												if($i==0) echo "<script> var testOut=[";
                                                                                                else echo "<script> testOut.push(";
                                                                                               /* echo "<script> var testOut=[";
                                                                                                for($i=0;$i<count($testOutput)-1;$i++){
                                                                                                        $testOutput[$i]=str_replace('\'','\\\'',$testOutput[$i]);
                                                                                                        echo "'$testOutput[$i]',";
                                                                                                }*/
                                                                                                $testOutput[$i]=str_replace('\'','\\\'',$row[tOut]);
                                                                                                if ($i==0) echo "'$testOutput[$i]']; </script>";
												else echo "'$testOutput[$i]'); </script>";
												if ($i==0) echo "<script> var testIn=[";
                                                                                                else echo"<script> testIn.push(";
                                                                                               /* echo "<script> var testIn=[";
                                                                                                for($i=0;$i<count($testInput)-1;$i++){
                                                                                                        $testInput[$i]=str_replace('\'','\\\'',$testInput[$i]);
                                                                                                        echo "'$testInput[$i]',";
                                                                                                }*/
                                                                                                $testInput[$i]=str_replace('\'','\\\'',$row[tIn]);
                                                                                                if ($i==0) echo "'$testInput[$i]']; </script>";
												else echo"'$testInput[$i]'); </script>";
                                                                                                $i++;
        								                }
                                                                                }
                                                                        }
								        ?>
								        <option value="end" <?php if(strcmp($_POST[selectSearch],"end")==0)echo "selected";?>>自訂測試檔</option>
								</select>
							</li>
							<li><a class="icon fa-play" onclick="" href="javascript:;" id="runbtn">執行(存檔)</a></li>
							<li><a class="icon fa-refresh"onclick="recode();" href="javascript:;" id="runbtn">還原</a></li>
<!--							<li><a class="icon fa-check"  onclick="OK();" href="javascript:;" id="runbtn">標記</a></li>-->
							
							<?php
/*        							$sql = "select * from exams where ID='$_GET[ID]'";
	        						$result = $conn1->query($sql);
		        					if($result->num_rows>0){
			        			        	$row = $result->fetch_assoc();
				        	        		echo "<li>$row[name]</li>";
					        		}*/
							?>
						</ul>
						<nav id="Nav">
							<ul class="actions">
							        <!--<li>IP:
							        <?php
		        					        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	        				        		        $myip = $_SERVER['HTTP_CLIENT_IP'];
        		        				        }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
				        		        	        $myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					        		        }else{
                							        $myip= $_SERVER['REMOTE_ADDR'];
		        					        }
			        				        echo $myip;
							        ?></li>-->
							        <li><font color="red"><center><a class="icon fa-clock-o" id="showbox"></a></center></font></li>
<!--								<li><font color="red"><center><a class="icon fa-hourglass-half" id="showbox"></a></center></font></li>-->
								<li></li>
								<li><center><?php echo "$_SESSION[name]";?></center></li>
								<li><a class="icon"  onclick="end();" href="javascript:;" id="runbtn">提前結束</a></li>
							</ul>
						</nav>
					</header>
					<header id="headerA" style="height:8%;width:100%"> 
			        		<ul class="actions">
	        	        			<li><a class="icon fa-bars"onclick="listFunction();" href="javascript:;" id="runbtn">功能　</a><?php echo "$_SESSION[name]";?>　
	        	        			<a class="icon"  onclick="end();" href="javascript:;" id="runbtn">提前結束</a></li>
        					</ul>
					</header>
					<div id="editor" ></div>
				<!--	<div id="testeditor"></div>-->
				<!-- Main -->
				<article id="main">
					<section class="wrapper style5">
					        <div id="hrdiv" onmousedown="hrdown(event)" onmouseup="hrup(event)" style="cursor:n-resize"></div>
					        <div id="hrdiv1" onmousedown="hr1down(event)" onmouseup="hr1up(event)" style="cursor:e-resize"></div>
						<div id="input">
						        <?php
						                //$sql = "select * from examPapersquestion where paperID='$_GET[pID]' AND ID='$_GET[qID]'";
						                $sql = "select * from PapersQuestion,PapersScore where PapersQuestion.PID='$_GET[PID]' AND PapersQuestion.QID='$_GET[QID]' AND PapersScore.PID=PapersQuestion.PID AND PapersScore.QID=PapersQuestion.QID";
						                $result = $conn2->query($sql);
						                $proLan="";
						                $className="";
						                if($result->num_rows > 0){
        						                while($row = $result->fetch_assoc()){
        						                        $proLan=$row[proLan];
        						                        $className=$row[className];
        						                        $i=$_GET[qnum];
        						                        echo "<span style='font-size:24px;'><center>$row[name]</center></span><span style='font-size:24px;'><center>(佔總分$row[score]%)</center></span><br><br>";
        						                        $content=str_replace("&lt;codeBox&gt;","<pre class='prettyprint' style='font-size:13px; overflow-x:auto;'>",$row[content]);
        						                        $content=str_replace("&lt;/codeBox&gt;","</pre>",$content);
        						                        $content=str_replace("<斜線>","/",$content);
										$content=str_replace("&lt;Space&gt;","<img src='../upimages/spaceImg.png' height='8' />",$content);
        						                        $content=str_replace("&lt;Enter&gt;","<img src='../upimages/enterImg.png' width='20' height='20' />",$content);
//        						                        $content=str_replace(" ","&nbsp;",$content);
        						                        //echo "<pre>$content</pre>";
        						                        echo "$content";
	        					                }
						                }
						        ?>
						</div>
						<div name="output" id="output" >
						        <?php
						                if(strcmp($_POST[selectSearch],"end")!=0){
						                        if(strcmp($testopenY,"1")==0){
					        	                        echo "<textarea name='testinput' id='testinput' style='height:80px;border:1px #b1b1b1 solid;display:none;' placeholder='請輸入自訂數值' required>$_POST[testinput]</textarea>";
				        		                }else{
			        			                        echo "<textarea name='testinput' id='testinput' style='height:80px;border:1px #b1b1b1 solid;' placeholder='請輸入自訂數值' required>$_POST[testinput]</textarea>";
		        				                }
	        				                }else{
        						                echo "<textarea name='testinput' id='testinput' style='height:80px;border:1px #b1b1b1 solid;' placeholder='請輸入自訂數值' required>$_POST[testinput]</textarea>";
						                }
						                
						        ?>
						        <div id="correctoutput">
                                                        <?php
                                                        /*
                                                                for($i=0;$i<count($testOutput);$i++){
                                                                        if(strcmp($_POST[selectSearch],"$i")==0){
                                                                                
                                                                                $output = str_replace("\\n","<font color='#FF0000'><換行符號></font><br>",$testOutput[$i]);
                                                                                echo "參考輸出：<br>".$output;
                                                                        }
                                                                }*/
                                                        ?>
						        </div>
						        <div id="userout" style="font-family: monospace; ">
							<div id="loadingImg" style="display:none"><br><center><img src="../upimages/loading.gif" width=100px height=100px></center></div>
							<p id="result"></p>
							
							</div>
						</div>
<!--						<iframe id="wetty" src="http://203.64.125.109:8080/wetty/ssh/cbb103054/compiler/x.c" onclick="myFunction()"></iframe>-->
						<div class="inner">
						        <textarea name="codetemp" id="codetemp" style="display:none"></textarea>
						        <textarea name="inputtemp" id="inputtemp" style="display:none"></textarea>
                    <textarea name="isMemoryTemp" id="isMemoryTemp" style="display:none">
                      <?php
                        $sqlT = "select * from QuestionTags where QID='$_GET[QID]'";
                        $resultT =  $conn1->query($sqlT);
                        $rowT = $resultT->fetch_assoc();
                        $mem = $rowT[mem_adr];
                        echo "$mem";
                      ?>
                    </textarea>
						        <iframe style="display:none" id="Iframe"></iframe>
						        <iframe style="display:none" id="IframeC"></iframe>
						</div>
					</section>
				</article>

			</div>
                </form>
		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.scrollex.min.js"></script>
			<script src="../assets/js/jquery.scrolly.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="../assets/js/main.js"></script>
			<script src="../ace-builds-master/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
			<script src="../ace-se-extends/editor-extends.js" type="text/javascript" charset="utf-8"></script>
<!--			<script>
			        var testeditor = ace.edit("testeditor");
			        testeditor.setTheme("ace/theme/tomorrow");
			        testeditor.session.setMode("ace/mode/text");
				testeditor.setFontSize(16);
				
			        
			        <?php
			                if(strcmp($_POST[selectSearch],"end")==0){
        			                $str=str_replace("\r\n","\\n",$_POST[inputtemp]);
	        		                echo "testeditor.setValue('$str');";
			                }
			                /*
        			        for($i=0;$i<count($testInput);$i++){
	                		        if(strcmp($_POST[selectSearch],"$i")==0){
        	                		        echo "
        	                		                testeditor.setValue('$testInput[$i]');
        	                		                testeditor.setReadOnly(true);
                                                        ";
	                		        }
                                        }
                                        */
                                ?>
			        
			</script>-->
			<script>
				var editor = ace.edit("editor");
				editor.setTheme("ace/theme/tomorrow");
				//editor.setTheme("ace/theme/vibrant_ink");
				editor.session.setMode("ace/mode/c_cpp");
				editor.setFontSize(16);
				se_extends.initEditor(editor);
				//editor.commands.removeCommand('find');
				//editor.commands.removeCommand('removeline');
				//editor.commands.removeCommand('replace');
				//editor.commands.removeCommand('selectall');
				/*綁定*/
				/*editor.commands.addCommand({
					name: 'myCommand',
					bindKey: {win: 'Ctrl-R',mac: 'Command-R'},
					exec: function(editor){alert(editor.getValue());},
					readOnly: true // false if this command should not apply in readOnly mode
				});
				/**/
				//editor.setValue("#include <stdio.h>\n\nint main(){\n\tint i;\n\tfor(i=0;i<=10;i++){\n\t\tprintf(\"%d\\n\",i);\n\t}\n}\n");
				//editor.getValue();
				//alert(editor.getValue());
				//editor.setReadOnly(true);//唯讀
        			<?php
        			        
        			        $sqlCode = "select * from PapersQuestion where PID='$_GET[PID]' AND QID='$_GET[QID]' ";
        			        $resultCode = $conn2->query($sqlCode);
        			        if($resultCode->num_rows>0){
                			        while($rowCode = $resultCode->fetch_assoc()){
                        			        echo "
        		        	                function heredoc(fn) {
        			                        return fn.toString().split('\\n').slice(1,-1).join('\\n') + '\\n'
        		        	                }
                	        		        var tmpl1 = heredoc(function(){/*
$rowCode[code]
*/});
                        	        	        tmpl1=tmpl1.replace(/<斜線>/g,'/');
        	                		        tmpl1=tmpl1.substring(0,tmpl1.length - 1);//刪除最後一個字元(換行)
                	        		        ";
                			        }
        			        }
	        		        $sqlZ = "select * from ExamScore where username='$_SESSION[user]' and EID='$_GET[EID]'";
                                        $resultZ = $conn2->query($sqlZ);
                                        if($resultZ->num_rows>0){
                                                echo "editor.setReadOnly(true);";
                                        }
					if(file_exists('../temp/'.$_SESSION[user].'/exam/'.$_GET[EID].'/'.$_GET[QID].'/code.c')){ 
	                        		echo "
							$.post('../temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$_GET[QID]."/code.c', function(data) {
								//editor.setValue(data);
								se_extends.setValue(editor, data);
                                                });
	                        		                //function heredoc(fn) {
                	        		                //        return fn.toString().split('\\n').slice(1,-1).join('\\n') + '\\n'
                                                                //}
	                		                        //var tmpl = heredoc(function(){

//});
                                                                //tmpl=tmpl.replace(/<斜線>/g,'/');
                                                                //tmpl=tmpl.substring(0,tmpl.length - 1);//刪除最後一個字元(換行)
	                		                        //editor.setValue(tmpl);
                                                        ";
	        		                //}
					}else if(file_exists('../temp/'.$_SESSION[user].'/exam/'.$_GET[EID].'/'.$_GET[QID].'/code.cpp')){
                                                echo "
                                                $.post('../temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$_GET[QID]."/code.cpp', function(data) {
							//editor.setValue(data);
							se_extends.setValue(editor, data);
                                                });
                                                ";
					}else if(file_exists('../temp/'.$_SESSION[user].'/exam/'.$_GET[EID].'/'.$_GET[QID].'/code.java')){
                                                echo "
                                                $.post('../temp/".$_SESSION[user]."/exam/".$_GET[EID]."/".$_GET[QID]."/code.java', function(data) {
                                                        //editor.setValue(data);
							se_extends.setValue(editor, data);
                                                });
                                                ";
	        		        }else{
						//echo "editor.setValue(tmpl1);";
	        		                echo "se_extends.setValue(editor, tmpl1);";
	        		        }
	        		        
	        		        
		        	?>

			</script>
<script>
            function inlog(action)
                        {
                          var NowDate=new Date();
　                        var h=NowDate.getHours();
　                        var m=NowDate.getMinutes();
　                        var s=NowDate.getSeconds();　
                          localStorage.setItem("log",localStorage.getItem("log")+h+"時"+m+"分"+s+"秒"+action+"\n");
                          var str=h+"時"+m+"分"+s+"秒"+action+"\n";
                          $.ajax({
                  type: "POST", 
                  url: "inlog.php",
                  dataType: "json", 
                  data: {
              EID:<?php echo $_GET[EID];?>, 
              reason:str 
                        },
                  success: function(data) 
                      {
                   if (data.ck)
                       {
            //    alert(data.ck);    
                    } 
                    else
                    {
                
                    }
              
            
                  },
              error: function(jqXHR) {
                         // alert(jqXHR.status);
                          }
    
                    })
            }
function checksave()
            {
              var check=0;
              var tempstr;
              var tempcode
              tempstr=se_extends.getValue(editor);
              var a="";
              tempstr=tempstr.replace(/(\/)/g,"<斜線>");
              tempstr=tempstr.replace(/\\/g,"\\\\");
              tempstr=tempstr.replace(/\'/g,"''");
              
              $.ajax({
                      type:"POST",
                      url:"codetemp.php",
                      dataType:"json",
                      data:{
                            codetemp:tempstr,      
                            EID:<?php echo $_GET[EID];?>,
                            QID:<?php echo $_GET[QID];?>,
                            usr:"<?php echo $_SESSION[user];?>",
			    proLan:"<?php echo $proLan;?>",
                            },
                      success:function(data)
                      {
                        if(data.codetemp)
                        {
 
                        }
                        else
                        {
 
                        }
                      }
                      })       
                        setTimeout("checksave()",30000);
             }
            </script>
<!--        		<script src="../ace-builds-master/src/ace.js"></script>
                        <script src="../ace-builds-master/src/ext-static_highlight.js"></script>  -->

	</body>
</html>
