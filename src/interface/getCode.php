<?php
	header('Access-Control-Allow-Origin:*');
	
	session_start();
	_code(4,$_GET['cwidth'],$_GET['cheight'],true);
	$_SESSION['dir'] = dirname(__FILE__);
	/**
	 * _code()验证码生成函数
	 * @param int $_rnd_code 验证码位数
	 * @param int $_width 验证码图像宽度
	 * @param int $_height 验证码图像高度
	 * @param boolean $_flag 平面旋转验证码
	 * @return 返回png类型图像
	 * 
	 * @建议:每增加一位,图像宽度增加25,默认4位,宽度75
	 * */
	function _code($_rnd_code=4,$_width=75,$_height=25,$_flag=false){
	    
	    //dechex($num) 将十进制int类型数据转换成十六进制的字符串
	    //mt_rand($min,$max) 在一定内范围生成int随机数,范围就是$min-$max
//	    for($i=0;$i<$_rnd_code;$i++){
//	        if(mt_rand(1, 10) >= 5)
//	           $_nmsg .= dechex(mt_rand(0, 15));//小写
//	        else
//	           $_nmsg .= strtoupper(dechex(mt_rand(0, 15)));//大写
//	    }
		//大小写+数字
		$_str = "abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for($i=0;$i<$_rnd_code;$i++){
			$_nmsg .= $_str[mt_rand(0,strlen($_str)-1)];
		}
	    
	    //$_SESSION[]是一个数组变量
	    //$_SESSION[]创建之前必须要开启,
	    //如果要跨页面传值必须要两边都要开启session
	    //session_start();
	    //将4位随机数保存在session中
	    $_SESSION['code'] = $_nmsg;
	    
	    //创建一张图像 imagecreatetruecolor(宽,高);
	    $_img = imagecreatetruecolor($_width, $_height);
	    
	    //为图像分配颜色 imagecolorallocate(图像,红,绿,蓝);
	    $_white = imagecolorallocate($_img, 255, 255, 255);
	    
	    //将颜色填充到图像中 imagefill(图像,从x轴哪里开始填充,从y轴哪里开始填充,颜色);
	    imagefill($_img, 0, 0, $_white);
	    
	    //黑色
//	    $_black = imagecolorallocate($_img, 0, 0, 0);
		//透明
		$_transparent = imagecolorallocatealpha($_img,0,0,0,127);
	    //为图像添加边框 其实就是画矩形 imagerectangle(图像,初始x,初始y,结束x,结束y,颜色);
	    imagerectangle($_img, 0, 0, $_width-1, $_height-1, $_transparent);
	    
	    //随机画6个线条
	    for($i=0;$i<6;$i++){
	        //颜色随机
	        $_rnd_color = imagecolorallocate($_img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
	        //线条位置随机 输出线条 imageline(图像,初始x,初始y,结束x,结束y,颜色);
//	        imageline($_img, mt_rand(1, $_width-2), mt_rand(1, $_height-2), mt_rand(1, $_width-2), mt_rand(1, $_height-2), $_rnd_color);
			//画圆,与验证码框相交呈现弧状曲线
			imagearc($_img,mt_rand(-$_width,$_width),mt_rand(-$_height,$_height),mt_rand(30,$_width*2),mt_rand(20,$_height*2),mt_rand(0,360),mt_rand(0,360),$_rnd_color);
	    }
	    
	    //随机画雪花,就是*号
	    for($i=0;$i<100;$i++){
	        //浅色随机
	        $_rnd_color = imagecolorallocate($_img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
	        //输出字符串 imagestring(图像,字符串的x,字符串的y,字符串,颜色);
	        imagestring($_img, 1, mt_rand(1, $_width-7), mt_rand(1, $_height-7), '*', $_rnd_color);
	    }
	    
	    //随机验证码,将字符串画在图像中
	    for($i=0;$i<strlen($_SESSION['code']);$i++){
	        if(!$_flag){
    	        //验证码有4位,分别占据1/4,所以要用$i/4来确定各自的位置,+ mt_rand(1, 10)只为调整距离
    	        imagestring($_img, mt_rand(4, 6), $_width*($i/$_rnd_code) + mt_rand(1, 10), mt_rand(1, $_height/3), $_SESSION['code'][$i], imagecolorallocate($_img, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200)));
	        }else{
    	        //imagettftext(图像,字体大小,平面旋转角度(顺逆),基准x,基准y,颜色,[字体文件路径,不能为空],字符串) 大约是基准点在左下角(因为中文和英文字符不同,英文字符在边缘,而中文字符则穿过中下部),而imagestring()在左上角
    	        imagettftext($_img, 20, mt_rand(-30, 30), $_width*($i/$_rnd_code) + mt_rand(10, 30), mt_rand(15, $_height), imagecolorallocate($_img, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200)), dirname(__FILE__)."/consola.ttf", $_SESSION['code'][$i]);
	        }
	    }
	    
	    //输出图像,分两步:1,指定什么类型的图片;2,按什么类型的方式输出图片
	    header('Content-Type:image/png');
	    imagepng($_img);
	    
	    //销毁图像
	    imagedestroy($_img);
	}

?>
