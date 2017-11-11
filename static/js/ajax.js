let ajax = (opt) => {
	opt = opt || {};
	opt.type = (opt.type || "GET").toUpperCase();
	opt.async = opt.async==undefined ? true : opt.async;
	opt.overtime = opt.overtime || 3000;
	let params = formatData(opt.data);
	let timer = null;
	let xhr;
	
	if(window.XMLHttpRequest)
		xhr = new XMLHttpRequest();
	else
		xhr = ActiveXObject("Microsoft.XMLHttp");
	
	if(opt.beforeSend && opt.beforeSend() === false)
		return false;
	return new Promise((resolve,reject) => {
		xhr.onreadystatechange = () => {
			if(xhr.readyState == 4){
				if(xhr.status >= 200 && xhr.status < 300){
					clearTimeout(timer);
					resolve(JSON.parse(xhr.responseText),xhr.responseXML);
				}
				else
					reject(xhr.status);
			}
		}
	
		if(opt.type == "GET"){
			xhr.open("GET",opt.url+"?"+params,opt.async);
			xhr.send(null);
		}else if(opt.type == "POST"){
			xhr.open("POST",opt.url,opt.async);
			xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xhr.send(params);
		}
		
		timer = setTimeout(() => {
			xhr.abort();
			opt.complete && opt.complete("请求超时,请检查网络设置");
		},opt.overtime);
	})
	
}

let formatData = (data) => {
	let arr = [];
	for(let x in data)
		arr.push(encodeURIComponent(x)+"="+encodeURIComponent(data[x]));
	arr.push("timestamp="+(+new Date()));
	return arr.join("&");
}
export default ajax;