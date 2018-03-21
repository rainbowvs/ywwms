let formatData = (flag,data) => {
	if(flag == undefined){
		let arr = [];
		for(let x in data)
			arr.push(encodeURIComponent(x)+"="+encodeURIComponent(data[x]));
		arr.push("timestamp="+(+new Date()));
		return arr.join("&");
	}else{
		let formData = new FormData();
		for(let x in data)
			formData.append(x,data[x]);
		formData.append("timestamp",+new Date());
		return formData;
	}
}

let ajax = (opt) => {
	opt = opt || {};
	opt.type = (opt.type || "GET").toUpperCase();
	opt.async = opt.async==undefined ? true : opt.async;
	opt.overtime = opt.overtime || 8000;
	let params = formatData(opt.RequestHeader,opt.data);
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
						resolve(JSON.parse(xhr.responseText),xhr.responseXML);
					}catch(e){
						throw '请求返回的数据类型不是JSON字符串';
					}
				}else
					reject(xhr.status);
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
		}
		
		timer = setTimeout(() => {
			reject('网络超时');
			xhr.abort();
		},opt.overtime);
	});
}
export default ajax;