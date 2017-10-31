<?php
	header('Access-Control-Allow-Origin:*');
	header('Content-type:text/html;charset=utf-8;');
	session_start();
	echo $_SESSION['dir'];
//	if(strtolower($_GET['code']) === strtolower($_SESSION['code']))
//		echo "正确";
//	else
//		echo "错误";
//	if(strcasecmp($_GET['code'],$_SESSION['code']) != 0)
//      echo '{"type":"fail","msg":"验证码错误"}';
//  else
//  	echo '{"type":"success","msg":"验证码正确"}';
//	echo '{"scode": '.'"'.$scode.'"'.'}';
?>