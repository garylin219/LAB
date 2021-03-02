<?php
session_start();
require "session.php";
require "../mysql.php";
?>
<html>
	<head>
		<title>Smart Exam</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="stylesheet" href="../cssmenu/styles.css">
		<script src="../assets/js/jquery-latest.min.js" type="text/javascript"></script>
		<script src="../cssmenu/script.js"></script>
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
		<script src="../sweetalert/sweetalert-dev.js"></script>
		<link rel="stylesheet" href="../sweetalert/sweetalert.css">
		<link rel="stylesheet" type="text/css" href="../ace-se-extends/editor-extends.css">
		<link rel="stylesheet" type="text/css" href="../se/css/tabbedPane.css">
		<style type="text/css" media="screen">
			#output{
				//padding-top:15px;
				//padding-left:10px;
				//padding-right:30px;
				margin: 0;
				position: absolute;
				top: 60%;
				bottom: 0;
				left: 35%;
				height: 39%;
				right: 0;
				overflow-x:auto;
				overflow-y:auto;
			}
			/*#editor {*/
			#editorTabbedPane {
				margin: 0;
				position: absolute;
				top: 8%;
				bottom: 0;
				left: 35%;
				height: 52%;
				right: 0;
			}
			/*#wetty{
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
				//padding-top:15px;
				//padding-left: 20px;
				//padding-right: 20px;
				top: 8%;
				bottom: 0;
				left: 0%;
				right: 0;
				height: 92%;
				width: 35%;
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
				left: 35%;
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
			/*
			.tab{
				overflow: hidden;
				background-color: #f1f1f1;
				//height: auto;
			}

			.tab button{
				background-color: inherit;
				float: left;
				border: none;
				outline: none;
			}

			.tab button:hover{
				background-color: #ddd;

			}

			.tab button.active{
				background-color: #ccc;
			}

			.tabcontent{
				display: none;
				padding: 20px 20px;
			}
*/
		</style>
		<script type="module">
			import * as sedate from '../se/js/date.js';
			window.addEventListener('load', function() {
				sedate.displayDateTime("showbox");
			});
		</script>
		<script>
			var selectVal=-1;
			function changed(theselect) {
				if(theselect.value!="end"){
					document.getElementById("testinput").style.display="none";
					selectVal=theselect.value;
				}else{
					document.getElementById("testinput").focus();
					document.getElementById("testinput").style.display="block";
					selectVal=-1;
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
						se_extends.setValue(editor, "");
						swal.close();
					}
				});
				return false;
			}
			/*
			function run(){
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

				document.getElementById("codetemp").innerHTML=tempstr;
				if(selectVal!=-1){
					tempstr=testIn[selectVal];
				}else{
					tempstr=document.getElementById("testinput").value;
				}

				document.getElementById("inputtemp").innerHTML=tempstr;
				window.onbeforeunload =false;
				document.getElementById('form1').submit();
			}
			 */
		</script>
		<script>
			/*wetty focus*/
			function checkFocus() {
				if(document.activeElement == document.getElementsByTagName("iframe")[0]) {
					//console.log('iframe has focus');
					return true;
				} else {
					//console.log('iframe not focused');
					return false;
				}
			}
		</script>
		<script>
			var hrD,hr1D;
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
						//document.getElementById("editor").style.height=(e.pageY-document.getElementById("editor").offsetTop-1);

						document.getElementById("editorTabbedPage").style.height=(e.pageY-document.getElementById("editorTabbedPage").offsetTop-1);
						//document.getElementById("editorContent").style.height=(e.pageY-document.getElementById("editorContent").offsetTop-1);
						var editors = etp1.container.editor;
						for(var edt in editors){
							editors[edt].resize();
						}
						//editor.resize();
						//testeditor.resize();
					}
				}
				if(e.pageX<(document.body.clientWidth*0.9) && e.pageX>(document.body.clientWidth*0.1)){
					if(hr1D==1){
						document.getElementById("hrdiv1").style.left=e.pageX-1;
						document.getElementById("input").style.width=e.pageX-1;
						//document.getElementById("testeditor").style.width=e.pageX-1;
						//document.getElementById("editor").style.left=e.pageX+1;
						document.getElementById("editorTabbedPage").style.left=e.pageX+1;
						document.getElementById("output").style.left=e.pageX+1;
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

			function openPage(elm, pageId) {
				var par = elm.parentElement.parentElement;
				var i, tabcontent, tablinks;
				tabcontent = par.getElementsByClassName("tabcontent");
				for(i = 0; i < tabcontent.length; i++) {
					tabcontent[i].style.display = "none";
				}
				tablinks = par.getElementsByClassName("tablinks");
				for(i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" active", "");
				}
				document.getElementById(pageId).style.display = "block";
				elm.className += " active";
			}
			function checkName(sele){
				if(sele.value=="JAVA"){
					document.getElementById("classDiv").style="";
					document.getElementById("className").required=true;
				}else{
					document.getElementById("classDiv").style="display:none";
					document.getElementById("className").value="";
					document.getElementById("className").required=false;
				}
			}

			function previewQuestion(){
				var title = document.getElementById("name").value;
				var content=tinymce.get("HTMLeditor").getContent();
				content = content.replace(/&lt;codeBox&gt;/g,"<pre class='prettyprint' style='font-size:13px; overflow-x:auto;'>");
				content = content.replace(/&lt;\/codeBox&gt;/g,"</pre>");
				content = content.replace(/&lt;Space&gt;/g,"<img src='../upimages/spaceImg.png' height='8' />");
				content = content.replace(/&lt;Enter&gt;/g,"<img src='../upimages/enterImg.png' width='20' height='20' />");
        var testContent = document.getElementById("testFileContentTemp").innerText;
				document.getElementById('previewTitle').innerHTML = title;
				document.getElementById('previewContent').innerHTML = content + testContent;
			}
		</script>
		<script>
			function addTestFile(inputValue, outputValue, scoreValue) {
				var iv=inputValue||"", ov=outputValue||"", sv=scoreValue||"";
				var content = document.getElementById('testfileContent');
				var n = testfilePage.getElementsByClassName('testfile').length;
				if(n>=10) {
					swal("測試檔限制為10個！");
					return;
				}
				var testfile = document.createElement('ul');
				testfile.setAttribute('class','actions testfile');
				testfile.setAttribute('id','test'+n);
				var innerHTML = "";
				innerHTML+="<li class='testfileName'>測試檔"+(n+1)+" ：</li>";
				innerHTML+="<li><textarea id='testinput"+n+"' name='testinput"+n+"' class='testinput' placeholder='輸入'>"+iv+"</textarea></li>";
				innerHTML+="<li><textarea id='testoutput"+n+"' name='testoutput"+n+"' class='testoutput' placeholder='輸出'>"+ov+"</textarea></li>";
				innerHTML+="<li><input id='testscore"+n+"' name='testscore"+n+"' class='testscore' placeholder='配分' type='number' min='0' max='100' style='width: 5em;color:#000000;' step='any' value='"+sv+"'> %</li>";
				innerHTML+="<li><input type='checkbox' id='CHE"+n+"' class='CHE[]'><label class='CHEL' for='CHE"+n+"'></label></li>";
				testfile.innerHTML=innerHTML;
				content.appendChild(testfile);
			}
			function deleteTestFile() {
				var content=document.getElementById("testfileContent");
				var checkboxs=content.getElementsByClassName("CHE[]");
				var CHESelected=false;
				for(var i=0,len=checkboxs.length;i<len;i++){
					if(checkboxs[i].checked) {
						CHESelected=true;
						break;
					}
				}
				if(CHESelected){
					swal({
						title: "確定要刪除?",
						showCancelButton: true,
						closeOnConfirm: false,
						//closeModal: true,
					},
					function(inputValue){
						if(inputValue === false){
							return false;
						}
						var checkboxs,labels,name,input,output,score;
						checkboxs=content.getElementsByClassName("CHE[]");
						var elm=null;
            var testContent="",testBody="";
            testBody = tinymce.get("HTMLeditor").getBody();
						for(var i=checkboxs.length - 1;i>=0;i--){
							if(checkboxs[i].checked){
								elm=checkboxs[i].parentElement;
								elm=elm.parentElement;
								content.removeChild(elm);
							}
						}
						testfile=content.getElementsByClassName("testfile");
						name=content.getElementsByClassName("testfileName");
						input=content.getElementsByClassName("testinput");
						output=content.getElementsByClassName("testoutput");
						score=content.getElementsByClassName("testscore");
						checkboxs=content.getElementsByClassName("CHE[]");
						labels=content.getElementsByClassName("CHEL");
						for(var i=0,len=name.length;i<len;i++) {
							testfile[i].setAttribute("id","test"+i);
							name[i].innerHTML="測試檔"+(i+1)+" ：";
							input[i].setAttribute("id","testinput"+i);
							input[i].setAttribute("name","testinput"+i);
							output[i].setAttribute("id","testoutput"+i);
							output[i].setAttribute("name","testoutput"+i);
							score[i].setAttribute("id","testscore"+i);
							score[i].setAttribute("name","testscore"+i);
							checkboxs[i].setAttribute("id","CHE"+i);
							labels[i].setAttribute("for","CHE"+i);
						}
            previewQuestion()
						swal("測試檔刪除完成！");
					})
				}else{
					swal("請勾選測試檔！");
				}
			}
			function resetScore() {
				var content=document.getElementById("testfileContent");
				var score=content.getElementsByClassName("testscore");
				for(var i=0,len=score.length;i<len;i++){
					score[i].value="0";
				}
			}
		</script>
		<script>
		function execution() {
				sefunc.ajax({
					type: "POST",
					url: "../se/php/writecode.php",
					data: {
						proLan: document.getElementById('selectauthority1').value,
						className: document.getElementById('className').value,
						code: se_extends.getValue(editor),
					},
					callback: function(xhr) {
						if(xhr.readyState == 4 && xhr.status == 200) {
							sefunc.ajax({
								type: "POST",
								url: "../se/php/executionTest.php",
								data: {
									proLan: document.getElementById('selectauthority1').value,
									className: document.getElementById('className').value,
									input: document.getElementById('testinput').value,
								},
								callback: function(xhr){
									if(xhr.readyState == 4 && xhr.status == 200) {
										document.getElementById('userout').innerHTML=xhr.responseText;
									}
								}
							});
						}
					}
				});
			}
			function executionTestFile(){
				sefunc.ajax({
					type: "POST",
					url: "../se/php/writecode.php",
					data: {
						proLan: document.getElementById('selectauthority1').value,
						className: document.getElementById('className').value,
						code: se_extends.getValue(etp1.getEditor('ACEeditorAnswerCode')),
					},
					callback: function(xhr) {
						if(xhr.readyState == 4 && xhr.status == 200){
							var content = document.getElementById('testfileContent');
							var input = content.getElementsByClassName('testinput');
							var output = content.getElementsByClassName('testoutput');
							for(var i=0,len=input.length;i<len;i++){
								sefunc.ajax({
									type: "POST",
									url: "../se/php/executionTestFile.php",
									data: {
										proLan: document.getElementById('selectauthority1').value,
										className: document.getElementById('className').value,
										input: input[i].value,
                    isMemory: document.getElementById('isMemory').value,
									},
									callback: function(xhr){
										if(xhr.readyState == 4 && xhr.status == 200) {
											var value = xhr.responseText;
											value = value.replace(/&nbsp;/g," ");
											this.output.value = value;
										}
									},
									callbackpar:{
										index: i,
										output: output[i],
									}
								});
							}
						}
					}
				});
			}
			</script>
			<script>
			function getval(){
				var content=tinymce.get("HTMLeditor").getContent();
        var testContent = document.getElementById("testFileContentTemp").innerText;
        content = content.concat("",testContent);
				content=content.replace(/&/g,"&amp;");
				content=content.replace(/(\\)/g,"\\\\");
				content=content.replace(/(\/\*)/g,"<斜線>*");
				content=content.replace(/(\*\/)/g,"*<斜線>");
				content=content.replace(/\'/g,"''");

				//alert(content);
				document.getElementById("textcontent").innerHTML=content;

				var tempstr;
				tempstr=se_extends.getValue(etp1.getEditor('ACEeditor'));
				//tempstr=editor.getValue();
				tempstr=tempstr.replace(/(\/)/g,"<斜線>");
				tempstr=tempstr.replace(/\\/g,"\\\\");
				tempstr=tempstr.replace(/\'/g,"''");
				//alert(tempstr);
				document.getElementById("codetemp").innerHTML=tempstr;

				//Jun Wu , added for answer code
				var tempstr2;
				tempstr2=se_extends.getValue(etp1.getEditor('ACEeditorAnswerCode'));
				//tempstr2=editor2.getValue();
				tempstr2=tempstr2.replace(/(\/)/g,"<斜線>");
				tempstr2=tempstr2.replace(/\\/g,"\\\\");
				tempstr2=tempstr2.replace(/\'/g,"''");
				//alert(tempstr2);
				document.getElementById("codetemp2").innerHTML=tempstr2;
			}

      function exportTestFile(){
        var testFile=document.getElementById("testfileContent");
        var checkboxs=testFile.getElementsByClassName("CHE[]");
        var CHESelected=false;
        for(var i=0,len=checkboxs.length;i<len;i++){
          if(checkboxs[i].checked) {
            CHESelected=true;
            break;
          }
        }
        if(CHESelected){
          swal({
            title: "確定要匯出測試檔到題目?",
            showCancelButton: true,
            closeOnConfirm: false,
            //closeModal: true,
          },function(inputValue){
            if(inputValue==false){
              return false;
            }
            var editorContent = tinymce.get("HTMLeditor").getContent();
            //editorContent =editorContent.replace(/&/g,"&amp;");
            editorContent =editorContent.replace(/(\\)/g,"\\\\");
            editorContent =editorContent.replace(/(\/\*)/g,"<斜線>*");
            editorContent =editorContent.replace(/(\*\/)/g,"*<斜線>");
            editorContent =editorContent.replace(/\'/g,"''");
            //add
            var checkboxs,boxCount=[],count=0,testInput,testOuput,tempString;
            checkboxs=testFile.getElementsByClassName("CHE[]");
            for(var i=0;i<checkboxs.length;i++){
              if(checkboxs[i].checked){
                boxCount.push(i);
              }
            }
            editorContent="<div class='testFile'>";
            for(i in boxCount){
              testInput = document.getElementById("testinput"+boxCount[i]).value;
              testInput = testInput.replace(/ /g,"<img src='../upimages/spaceImg.png' height='8' />");
              testInput = testInput.replace(/(\r\n|\r|\n)/g,"<img src='../upimages/enterImg.png' width='20' height='20' /><br>");
              testOuput = document.getElementById("testoutput"+boxCount[i]).value;
              testOuput = testOuput.replace(/ /g,"<img src='../upimages/spaceImg.png' height='8' />");
              testOuput = testOuput.replace(/(\r\n|\r|\n)/g,"<img src='../upimages/enterImg.png' width='20' height='20' /><br>");
              count = count+1;
              tempString = "<div class='testTitle testID"+boxCount[i]+"''>"
                          +"<br /><span class=testName style='background-color: #ccffff;'>執行結果" + count + "</span><br />"
                          +"<table border='1'>"
                          +"<tbody>"
                          +"<tr>"
                          +"<td style='width: 34px;' bgcolor='yellow'><span style='color: #000000;'>輸入</span></td>"
                          +"<td><span style='color: #000000;'>" + testInput + "</span></td>"
                          +"</tr>"
                          +"<tr>"
                          +"<td bgcolor='pink'><span style='color: #000000;'>輸出</span></td>"
                          +"<td><span style='color: #000000;'>" + testOuput +"</span></td>"
                          +"</tr>"
                          +"</tbody>"
                          +"</table>"
                          +"</div>";
              editorContent = editorContent.concat("",tempString);
            }
            editorContent = editorContent.concat("","<br /></div>");
            document.getElementById("testFileContentTemp").innerHTML=editorContent;
            previewQuestion();
            swal("測試檔匯出完成！");
          })
        }
        else{
          swal("請勾選測試檔！");
        }
      }

      function resetTitleInf(){
        var testFileTemp=tinymce.get("HTMLeditor").getContent();
        var divStart = testFileTemp.indexOf("<div class=\"testFile\">");
        var divEnd = testFileTemp.lastIndexOf("</div>");
        var testFileContent = testFileTemp.substring(divStart,divEnd+6);
        var titleContent = testFileTemp.substring(0,divStart);
        if(divStart!=-1){
          document.getElementById("testFileContentTemp").innerHTML=testFileContent;
          tinymce.get('HTMLeditor').setContent(titleContent);
        }
      }

			function back(){
				window.location.href = "questions.php";
				return false;
			}

			function run(){
				<?php
$sql = "SELECT * from QuestionTags where QID='$_GET[ID]'";
$result = $conn1->query($sql);
if($result ->num_rows>0){
  $row = $result -> fetch_assoc();
  if (strcmp($row[mem_adr], "1") == 0) {
      echo "
                  document.getElementById('isMemory').selectedIndex='1';
                ";
  } else {
      echo "
                  document.getElementById('isMemory').selectedIndex='0';
                ";
  }
}

$sql = "select * from Question where QID='$_GET[ID]'";
$result = $conn1->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {

		//	echo $row[QID] . "<br>";
		//	echo $row[name] . "<br>";

		if (strcmp($row[founder], $_SESSION[user]) != 0) {
			echo "
									window.location.href = 'questions.php?msg=editError';
								";
		} else {
			echo "document.getElementById('degree').selectedIndex='$row[degree]';";
			if (strcmp($row[isPublic], "1") == 0) {
				echo "
										document.getElementById('selectauthority').selectedIndex='0';
									";
			} else {
				echo "
										document.getElementById('selectauthority').selectedIndex='1';
									";
			}
			if (strcmp($row[proLan], "JAVA") == 0) {
				echo "
									document.getElementById('selectauthority1').selectedIndex='2';
									document.getElementById('classDiv').style='';
									document.getElementById('className').required=true;
									document.getElementById('className').value='$row[className]';
									";
			} else if (strcmp($row[proLan], "CPP") == 0) {
				echo "
									document.getElementById('selectauthority1').selectedIndex='1';
									";
			} else {
				echo "
									document.getElementById('selectauthority1').selectedIndex='0';
									";
			}
			$name = str_replace("'", "\'", $row[name]);
			echo "
									document.getElementById('name').value='$name';
									function heredoc(fn) {
										return fn.toString().split('\\n').slice(1,-1).join('\\n') + '\\n'
									}
									var tmpl = heredoc(function(){/*
										$row[content]
									*/});
									tmpl=tmpl.replace(/<斜線>/g,'/');
									tmpl=tmpl.replace(/<codeBox>/g,'');
									tmpl=tmpl.substring(0,tmpl.length - 1);//刪除最後一個字元(換行)
									tinymce.get('HTMLeditor').setContent(tmpl);
                  resetTitleInf()
								";

			$sqlT = "select * from QuestionTest where QID='$_GET[ID]'";
			$resultT = $conn1->query($sqlT);
			$i = 0;
			if ($resultT->num_rows > 0) {
				//$i=0;
				while ($rowT = $resultT->fetch_assoc()) {
					$testscore[$i] = $rowT[score];
					//$testoutput = explode("\n", $row[testOutput]);
					//$testinput = explode("\n", $row[testInput]);
					//for($i=0;$i<count($testscore);$i++){
					$testoutput[$i] = str_replace('"', '\"', $rowT[tOut]);
					$testinput[$i] = str_replace('"', '\"', $rowT[tIn]);
					echo "
											addTestFile();
											document.getElementById('test" . $i . "').style.display='block';
											document.getElementById('testinput" . $i . "').value=\"$testinput[$i]\";
											document.getElementById('testoutput" . $i . "').value=\"$testoutput[$i]\";
											document.getElementById('testscore" . $i . "').value=\"$testscore[$i]\";
										";
					$i++;
				}
			} else {
				echo "addTestFile();";
			}
			echo "i=" . $i . ";";
			echo "document.getElementById('tag').value='$row[tags]';";
		}
	}
	$up = 0;
} else {
	$up = 1;
	echo "addTestFile();";
}
?>
			}
		</script>
	</head>
	<body onload="run()" style="background-color:#ffffff">
		<?php
if ($up == 1) {
	echo "<form method='post' action='Addquestion.php'>";
} else {
	echo "<form method='post' action='Upquestion.php?selectSearch=$_GET[selectSearch]&search=$_GET[search]&selectSearch1=$_GET[selectSearch1]&selectSearch2=$_GET[selectSearch2]&page=$_GET[page]'>";
}
?>
			<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
				<header id="header" style="height:8%">
					<ul class="actions">
						<li></li>
						<li><?php if ($up == 1) {echo "新增題目";} else {echo "編輯題目";}?></li><li></li><li></li>
						<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
						<li><a class="icon fa-play"  onclick="execution();" href="javascript:;" id="runbtn">執行</a></li>
						<li><a class="icon fa-refresh"  onclick="recode();" href="javascript:;" id="runbtn">還原</a></li>

					</ul>
					<nav id="Nav">
						<ul class="actions">
							<li><center id="showbox"></center></li>
							<li></li>
							<li><center><?php echo "$_SESSION[name]"; ?></center></li>
							<!--<li><a class="icon" href="#">儲存</a></li>-->
							<li><a class="icon" href="questions.php">離開</a></li>
						</ul>
					</nav>
				</header>
				<header id="headerA" style="height:8%;width:100%">
					<ul class="actions">
						<li><a class="icon fa-bars"onclick="listFunction();" href="javascript:;" id="runbtn">功能　</a><?php echo "$_SESSION[name]"; ?>
							<a class="icon" href="questions.php">離開</a></li>
					</ul>
				</header>
				<!-- Main -->
				<article id="main">
					<section class="wrapper style5">
						<div id="editorTabbedPane">
							<div class="tabBar">
							</div>
							<div class="tabContainer">
								<div id="helpPage" class="tabContent" style="font-size: 0.8em">
                    <span style='font-weight:bold;'>鎖定部份行程式碼不能編輯<br>功能說明：</span>
                    <br>
                    若需鎖定部份行程式碼，不能編輯，請選取要鎖定的範圍，並按下Alt+A。<br>
                    若需解除鎖定，請選取要解除的範圍，並按下Shift+Alt+A。<br>
                    <span style='font-weight:bold;'>只允許指定範圍可編輯<br>功能說明：</span>
                    <br>
                    特點：可以出填空題<br>
                    若需允許指定範圍程式碼，才可編輯，請選取要允許的範圍，並按下Alt+D。<br>
                    若需解除，請選取要解除的範圍，並按下Shift+Alt+D。<br>
								</div>
							</div>
						</div>
						<div id="hrdiv" onmousedown="hrdown(event)" onmouseup="hrup(event)" style="cursor:n-resize"></div>
						<div id="hrdiv1" onmousedown="hr1down(event)" onmouseup="hr1up(event)" style="cursor:e-resize"></div>
						<div id="input">
							<div class="tabBar">
							</div>
							<div class="tabContainer">
								<div id="editQuestion" class="tabContent" style="display: block; font-size: 0.8em">
									<ul class="actions">
										<li>語言　　：</li>
										<li>
											<select name="selectauthority1" id="selectauthority1" onchange="checkName(this)">
												<option value="C">C</option>
												<option value="CPP">C++</option>
												<option value="JAVA">Java　　</option>
											</select>

										</li>
									</ul>
									<ul id="classDiv" class="actions" style="display:none">
										<li>類別名稱：</li>
										<li><input type="text" id="className" name="className" size="30" placeholder="請不用填寫.java"></li>
									</ul>
									<ul class="actions">
										<li>權限　　：</li>
										<li>
											<select name="selectauthority" id="selectauthority">
												<option value="1">公開</option>
												<option value="0">私人</option>
											</select>

										</li>
									</ul>
									<ul class="actions">
										<li>題目名稱：</li>
										<li><input type="text" id="name" name="name" size="30" required></li>
									</ul>
									<ul class="actions">
				                                          <li>題目難度：</li>
                                					  <li>
                                          				    <select name="degree" id="degree">
                                                			      <option value="0">0</option>
                                                			      <option value="1">1</option>
                                                			      <option value="2">2</option>
                                                			      <option value="3">3</option>
                                                			      <option value="4">4</option>
                                                			      <option value="5">5</option>
                                                			      <option value="6">6</option>
                                                			      <option value="7">7</option>
                                                			      <option value="8">8</option>
                                                			      <option value="9">9</option>
                                                			      <option value="10">10</option>
                                        				    </select>
                                        				  </li>
                                 					 </ul>
                  <ul class="actions">
                    <li>記憶體題：</li>
                    <li>
                      <select name="isMemory" id="isMemory">
                        <option value="0">否</option>
                        <option value="1">是</option>
                      </select>  
                    </li>
                  </ul>
									<ul class="actions">
										<li>題目內容：</li>
										<li><textarea id="HTMLeditor" name="HTMLeditor" style="z-index: 101;" rows="4" cols="50" >

	<!--------------------------------------------------------------------------------------------------------->
	請設計一個C語言程式

	請參考右側的程式碼


	此題的執行結果可參考如下：
  <br />
  <br />
  <span style="background-color: #ccffff;">執行結果1</span><br />
	<table border="1">
	<tbody>
	<tr>
	<td style="width: 34px;" bgcolor="yellow"><span style="color: #000000;">輸入</span></td>
	<td><span style="color: #000000;">0&lt;Enter&gt;</span></td>
	</tr>
	<tr>
	<td bgcolor="pink"><span style="color: #000000;">輸出</span></td>
	<td><span style="color: #000000;">a_0=5&lt;Enter&gt;<br /></span></td>
	</tr>
	</tbody>
	</table>
	<br /><span style="background-color: #ccffff;">執行結果2</span><br />
	<table border="1">
	<tbody>
	<tr>
	<td style="width: 34px;" bgcolor="yellow"><span style="color: #000000;">輸入</span></td>
	<td><span style="color: #000000;">-1&lt;Enter&gt;</span></td>
	</tr>
	<tr>
	<td bgcolor="pink"><span style="color: #000000;">輸出</span></td>
	<td><span style="color: #000000;">Error!&lt;Enter&gt;<br /></span></td>
	</tr>
	</tbody>
	</table>
	<br />
	<!--------------------------------------------------------------------------------------------------------->
										</textarea></li>
										<br>若需要放上程式碼，請使用&lt;codeBox&gt;&lt;/codeBox&gt;。
										<br>若需表示空格符號，請使用&lt;Space&gt;。
										<br>若需表示換行符號，請使用&lt;Enter&gt;。
									</ul>
									<hr>
									<ul class="actions">
										<li>標籤　　：</li>
										<li><input type="text" id="tag" name="tag" size="30" placeholder=""></li>
									</ul>
									<ul class="actions">
										<li><button id="okbtn" onclick="getval()">送出</button></li>
										<li><button id="backbtn" onclick="return back();">取消</button></li>
									</ul>
								</div>
								<div id="previewQuestion" class="tabContent">
									<span style='font-size:24px;'><div id="previewTitle" style="text-align:center;"></div></span><br><br>
									<div id="previewContent"></div>

								</div>
							</div>
						</div>
						<div name="output" id="output">
							<div class="tabBar">
							</div>
							<div class="tabContainer">
								<div id="resultPage" class="tabcontent" style="display: block">
									<textarea name='testinput' id='testinput' style='height:80px;border:1px #b1b1b1 solid;' placeholder='請輸入自訂數值'></textarea>
									<div id="correctoutput">
									</div>
									<div id="userout" style="font-family: monospace; ">
									</div>
								</div>
								<div id="testfilePage" class="tabcontent" style="font-size: 0.8em">
									<ul class="actions">
										<li>
											<button type="button" onclick="addTestFile()">新增測試檔</button>
											<button type="button" onclick="deleteTestFile()">刪除測試檔</button>
											<button type="button" onclick="executionTestFile()">輸出測試檔</button>
											<button type="button" onclick="resetScore()">配分歸零</button>
                      <button type="button" onclick="exportTestFile()">匯出到題目內容</button>
										</li>
									</ul>
									<ul class="actions">
										<li><font color="blue">未給配分比例的題目將會由系統平均分配，若總和不是100%將會照比例自動調整。</font><br>
										<font color="red">請注意測試檔如需換行請直接按下Enter鍵，換行符號也是系統判定的一部份。</font></li>
									</ul>
									<div id="testfileContent">
									</div>
								</div>
							</div>
						</div>
						<!--<iframe id="wetty" src="http://203.64.125.109:8080/wetty/ssh/cbb103054/compiler/x.c" onclick="myFunction()"></iframe>-->
						<div class="inner">
							<textarea id="textcontent" name="textcontent" style="display:none"></textarea>
							<input type="text" id="ID" name="ID" style="display:none" value="<?php echo "$_GET[ID]"; ?>">
							<textarea name="codetemp" id="codetemp" style="display:none"></textarea>
							<textarea name="codetemp2" id="codetemp2" style="display:none"></textarea>
              <textarea name="testFileContentTemp" id="testFileContentTemp" style="display:none"></textarea>
<!--							<textarea name="inputtemp" id="inputtemp" style="display:none"></textarea>-->
						</div>
					</section>
				</article>
			</div>
		</form>
		<!-- Scripts -->
		<script src="../tinymce/tinymce.min.js"></script>
		<script src="../assets/js/jquery.min.js"></script>
		<script src="../assets/js/jquery.scrollex.min.js"></script>
		<script src="../assets/js/jquery.scrolly.min.js"></script>
		<script src="../assets/js/skel.min.js"></script>
		<script src="../assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="../assets/js/main.js"></script>
		<script src="../ace-builds-master/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
		<script src="../ace-se-extends/editor-extends.js" type="text/javascript" charset="utf-8"></script>
		<script src="../se/js/tabbedPane.js" type="text/javascript" charset="uft-8"></script>
		<script src="../se/js/ajax.js" type="text/javascript" charset="utf-8"></script>
		<script>
			var etp1=sefunc.initEditorTabbedPane('editorTabbedPane',true);
			etp1.addEditorTabPage('ACEeditor',"預設程式碼", true);
			etp1.addEditorTabPage('ACEeditorAnswerCode',"解答程式碼",true);
			etp1.setTabPage('helpPage','輔助說明');
			etp1.setTabPageActive('ACEeditor');
			for(var id in etp1.getTabButtonList()) {
				(function(id){
					this.addEventListener("click", function(){
						editor = etp1.getEditor(id);
					});
				}).call(etp1.getTabButton(id),id);
			}
			var editor = etp1.getEditor('ACEeditor');
			(function(){
				var tp;
				tp = sefunc.initTabbedPane('input','true');
				tp.setTabPage('editQuestion','編輯題目');
				tp.setTabPage('previewQuestion','預覽題目');
				tp.setTabPageActive('editQuestion');
				tp.getTabButton('previewQuestion').addEventListener("click", previewQuestion);

				tp = sefunc.initTabbedPane('output','true');
				tp.setTabPage('resultPage','輸出結果');
				tp.setTabPage('testfilePage','編輯測試檔');
				tp.setTabPageActive('resultPage');

			})();
			<?php
$sql = "select * from Question where QID='$_GET[ID]'";
$result = $conn1->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		echo "
							function heredoc(fn) {
								return fn.toString().split('\\n').slice(1,-1).join('\\n') + '\\n'
							}
							var tmpl = heredoc(function(){/*
$row[code]
*/});
							tmpl=tmpl.replace(/<斜線>/g,'/');
							tmpl=tmpl.substring(0,tmpl.length - 1);//刪除最後一個字元(換行)
							//editor.setValue(tmpl);
							se_extends.setValue(etp1.getEditor('ACEeditor'), tmpl);
						";
	}
}
?>
			<?php
$sql = "select * from Question where QID='$_GET[ID]'";
$sql = "select * from Question where QID='$_GET[ID]'";
$result = $conn1->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		echo "
							function heredoc2(fn) {
								return fn.toString().split('\\n').slice(1,-1).join('\\n') + '\\n'
							}
							var tmpl2 = heredoc2(function(){/*
$row[answerCode]
*/});
							tmpl2=tmpl2.replace(/<斜線>/g,'/');
							tmpl2=tmpl2.substring(0,tmpl2.length - 1);//刪除最後一個字元(換行)
							//editor2.setValue(tmpl2);
							se_extends.setValue(etp1.getEditor('ACEeditorAnswerCode'), tmpl2);
						";
	}
}
?>

		</script>
	</body>
</html>
