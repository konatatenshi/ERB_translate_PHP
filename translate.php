<?php
$url = isset($_GET["fname"])?$_GET["fname"]:'';
$down = isset($_GET["down"])?$_GET["down"]:'';
$password = isset($_REQUEST["password"])?$_REQUEST["password"]:'';
$filename = str_replace('/','_',$url);
$filename = str_replace('\\','_',$filename);
$filename = isset($_GET["upload"])?$_GET["upload"]:$filename;
$url = str_ireplace(".erb", "", $url);
if(empty($password)){
	echo '请输入密码以便访问该文件。可将密码告诉他人从而多人协助编辑。勿输入特殊字符避免无法访问。';
	echo "<form action=\"translate.php?fname=".$url."\" method=\"POST\">";
	echo "<input type=\"text\" name=\"fname\" value=\"".$url."\" style=\"display: none;\"><br />";
	echo "密码：";
	echo "<input type=\"password\" name=\"password\" ><br />";
	echo "<input type=\"submit\" name=\"submit\" value=\"提交\">";
	echo "</form>";
	exit;
}
$filefinal = "fanyi\\".$filename.$password.".ERB";
if(file_exists($filefinal)){
	//echo $filefinal.' 存在！';
	$file2 = file_get_contents("fanyi\\".$filename.$password.".ERB");
}else{
	//echo $filefinal.' 不存在！';
	$file = file_get_contents('C:\\wwwroot\\121.37.84.45\\ERB\\'.$url.'.ERB');
	$pattern = "/\r\n(?<!;)([\xC2\xA0\f\t\v]*)PRIN(\w+)(\s*)([\S\'\"]+)/";
	$replacement = "\r\n;".'${1}PRIN${2}${3}${4}${5}${0}';
	$file2 = preg_replace($pattern,$replacement,$file);
	$myfile = fopen("fanyi\\".$filename.$password.".ERB", "w") or die("错误!");
	fwrite($myfile, $file2);
	fclose($myfile);
}
if(isset($_GET["down"])){
	if($file2 == ''){
		echo '无内容，无法下载';
		exit;
    //如果没有内容可供下载，这里可以写入判断代码，比如打开一个提示页等
	}else{
		$center= $file2;
	}
	header("Content-Type: application/octet-stream");
	$ua = $_SERVER["HTTP_USER_AGENT"]; $filename = $filename.".ERB";//生成的文件名
	$encoded_filename = urlencode($filename);
	$encoded_filename = str_replace("+", "%20", $encoded_filename);
	if (preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']) ) { 
		header('Content-Disposition:  attachment; filename="' . $encoded_filename . '"');
	} elseif (preg_match("/Firefox/", $_SERVER['HTTP_USER_AGENT'])) { 
		// header('Content-Disposition: attachment; filename*="utf8' .  $filename . '"');
		header('Content-Disposition: attachment; filename*="' .  $filename . '"');
	} else { 
		header('Content-Disposition: attachment; filename="' .  $filename . '"');
	}
	echo $center;
	exit;
}elseif(isset($_GET["save"])){
	$yuanwen = isset($_GET["yuanwen"])?$_GET["yuanwen"]:'';
	$fanyi = isset($_GET["fanyi"])?$_GET["fanyi"]:'';
	$pattern2 = "/;([\xC2\xA0\f\t\v]*)PRIN(\w+)(\s)".$yuanwen."\r\n(?<!;)([\xC2\xA0\f\t\v]*)PRIN(\w+)(\s)(\S+)/";
	$replacement2 = ";".'${1}PRIN${2}${3}'.$yuanwen."\r\n".'${4}PRIN${5}${6}'.$fanyi;
	$strfanyi = preg_replace($pattern2,$replacement2,$file2);
	$myfile = fopen("fanyi\\".$filename.$password.".ERB", "w") or die("{\"code\":400}");
	fwrite($myfile, $strfanyi);
	fclose($myfile);
	echo "{\"code\":200}";
	//var_dump($strfanyi);
	exit;
}elseif(isset($_GET["upload"])){
	$upload = isset($_GET["upload"])?$_GET["upload"]:'';
	if ( 0 < $_FILES['file1']['error'] ) {
        echo 'Error: ' . $_FILES['file1']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file1']['tmp_name'], 'uploads/' . $upload.$password.".ERB");
    }
	$file3 = file_get_contents("uploads\\".$upload.$password.".ERB");
	if(!empty($file3)){
		$pattern2 = "/;([\xC2\xA0\f\t\v]*)PRIN(\w+)(\s)(\S+)\r\n(?<!;)([\xC2\xA0\f\t\v]*)PRIN(\w+)(\s)(\S+)\r\n/";
		preg_match_all($pattern2,$file3,$matches);
		$regarr = array();
		$strarr = array();
		foreach ($matches[8] as $k => $v) {
			$regarr[] = "/;([\xC2\xA0\f\t\v]*)PRIN(\w+)(\s)".$matches[4][$k]."\r\n(?<!;)([\xC2\xA0\f\t\v]*)PRIN(\w+)(\s)(\S+)/";
			$strarr[] = ";".'${1}PRIN${2}${3}'.$matches[4][$k]."\r\n".'${4}PRIN${5}${6}'.$v;
		}
		$strfanyis = preg_replace($regarr,$strarr,$file2);
		$myfile = fopen("fanyi\\".$upload.$password.".ERB", "w") or die("{\"code\":400}");
		fwrite($myfile, $strfanyis);
		fclose($myfile);
		echo "{\"code\":200}";
	}
	exit;
}
//preg_match_all($pattern,$file,$dir);
//var_dump($dir);
//echo $file2;
$pattern2 = "/;([\xC2\xA0\f\t\v]*)PRIN(\w+)(\s)(\S+)\r\n(?<!;)([\xC2\xA0\f\t\v]*)PRIN(\w+)(\s)(\S+)\r\n/";
preg_match_all($pattern2,$file2,$matches);
//echo json_encode($matches);
?>

<!DOCTYPE HTML>  
<html>  
<head>  
<meta charset="UTF-8" />  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<meta name="robots" content="none" />  
<title>翻译ERB线上协助平台-By tianshi</title>  
<style>  
*{font-family:"Microsoft Yahei";margin:0;font-weight:lighter;text-decoration:none;text-align:center;line-height:2.2em;}  
html,body{height:100%;}  
h1{font-size:100px;line-height:1em;}  
table{width:100%;height:100%;border:0;}  
input{width:99%;}
.a{width:45%;}
.b{width:7%;}
.c{width:3%;}
.i{background-color: #fc5531;color: #fff;}
</style>  
<script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/jquery/3.6.0/jquery.min.js" type="application/javascript"></script>
<script src="translate.js?017" type="application/javascript"></script>
</head>  
<body>
<?php echo "<input type=\"text\" id=\"password\" value=\"".$password."\" style=\"display: none;\"><br />";?>
<table cellspacing="0" cellpadding="0">  
<tr>  
<td>
<p>
	<table style="width:100%;height:18px;" cellpadding="2" cellspacing="0" border="2" bordercolor="#000000">
		<tbody>
			<tr>
				<td class="c">
					<span style="color:red;">序号</span>
				</td>
				<td class="a">
					<span style="color:red;">原文</span><input style="width:200px" id="file1" type="file" name="file1" accept="*.ERB"><input style="width:120px" type="button" value="导入字典文件" onclick="onUpload('<?php echo $filename;?>')"><button onclick="alert('服务器空间太少了，自己本地备份吧')">服务器被份</button>
				</td>
				<td class="a">
					<span style="color:red;">译文</span><button onclick="dc();">导出字典文件（记得先保存）</button>
				</td>
				<td class="b">
					<span style="color:red;">百度机翻</span>
				</td>
			</tr>
<?php
foreach ($matches[8] as $k => $v) {
    echo '			<tr>
				<td class="c">
					'.($k+1).'
				</td>
				<td class="a">
					<input type="text" readonly="readonly" id="yw'.$k.'" value=\''.$matches[4][$k].'\'></input>
				</td>
				<td class="a">
					<input type="text" id="tr'.$k.'" value=\''.$v.'\' onkeydown="check('.$k.')"></input>
				</td>
				<td class="b">
					<button onclick="tr('.$k.')">机翻</button><button onclick="sv('.$k.')">保存</button>
				</td>
			</tr>';
}
?>
		</tbody>
	</table>
</p>  
</td>  
</tr>  
</table>  
</body>  
</html>  