let isObj = (object) => {
	//用三种判断类型的方法保证是json的类型
	return object && typeof (object) === 'object' && object.constructor === Object && Object.prototype.toString.call(object) === "[object Object]";
};

let isArray = (object) => {
	//用三种判断类型的方法保证是json的子元素是数组类型
	return object && typeof (object) === 'object' && object.constructor === Array && Object.prototype.toString.call(object) === '[object Array]';
};

let getLength = (object) => {
	var count = 0;
	for (var i in object)
		count++;
	return count;
};

let compareObj = (objA, objB, flag) => {
	for (var key in objA) {
		if(!flag)//跳出整个循环
			break;
		if(!objB.hasOwnProperty(key)){
			flag = false;
			break;
		}
		if(!isArray(objA[key])){//子级不是数组时,比较属性值
			if(objB[key] != objA[key]){
				flag = false;
				break;
			}
		}else{
			if(!isArray(objB[key])){
				flag = false;
				break;
			}
			var oA = objA[key], oB = objB[key];
			if(oA.length != oB.length){
				flag = false;
				break;
			}
			for(var k in oA){
				if(!flag)//这里跳出循环是为了不让递归继续
					break;
				flag = compareObj(oA[k], oB[k], flag);
			}
		}
	}
	return flag;
};

let compareJson = (objA, objB) => {
	if(!isObj(objA) || !isObj(objB))
		return false; //判断类型是否正确
	if(getLength(objA) != getLength(objB))
		return false; //判断长度是否一致
	return compareObj(objA, objB, true);//默认为true
};

export default compareJson;