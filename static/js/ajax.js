let formatData = (flag,data,time) => {
	if(flag == undefined){
		let arr = [];
		for(let x in data)
			arr.push(encodeURIComponent(x)+"="+encodeURIComponent(data[x]));
		arr.push("time="+time);
		return arr.join("&");
	}else{
		let formData = new FormData();
		for(let x in data)
			formData.append(x,data[x]);
		formData.append("time="+time);
		return formData;
	}
}

let ajax = (opt) => {
	opt = opt || {};
	opt.type = (opt.type || "POST").toUpperCase();
	opt.async = opt.async==undefined ? true : opt.async;
	opt.overtime = opt.overtime || 8000;
	let date = new Date();
	opt.moment = date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
	let params = formatData(opt.RequestHeader,opt.data,opt.moment);
	let timer = null;
	let xhr;
	
	if(window.XMLHttpRequest)
		xhr = new XMLHttpRequest();
	else
		xhr = ActiveXObject("Microsoft.XMLHttp");
	
	return new Promise((resolve,reject) => {
		if(opt.beforeSend && opt.beforeSend() === false)
			reject('请求在发送前被取消');
		xhr.onreadystatechange = () => {
			if(xhr.readyState == 4){
				if(xhr.status >= 200 && xhr.status < 300){
					clearTimeout(timer);
					try{
//						console.group('%cResponse','font-size:16px;color:darkred');
						console.log(JSON.parse(xhr.responseText));
						console.groupEnd();
						resolve(JSON.parse(xhr.responseText),xhr.responseXML);
					}catch(e){
						throw '请求返回的数据类型不是JSON字符串';
					}
				}else
					reject(xhr.responseText);
			}
		}
	
		if(opt.type == "GET"){
			xhr.open("GET",opt.url+"?"+params,opt.async);
			xhr.send(null);
		}else if(opt.type == "POST"){
			xhr.open("POST",opt.url,opt.async);
			if(opt.RequestHeader == undefined)//RequestHeader有值时用multipart/form-data , 否则↓
				xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xhr.send(params);
			console.group('Request');
			console.log('%c'+opt.name,'font-weight:bold;font-size:16px;color:#9966CC');
			console.log('%c'+opt.moment,'font-weight:bold;font-size:16px;');
			console.log('%c'+opt.url,'font-size:20px');
			console.log(opt.data);
//			console.groupEnd();
		}
		
		timer = setTimeout(() => {
			reject('网络超时');
			xhr.abort();
		},opt.overtime);
	});
}
export default ajax;