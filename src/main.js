// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import App from './App';
import router from './router';
import store from '@/vuex/store';
import Loading from '@/components/common/Loading';
import Warn from '@/components/common/Warn';
import Dialog from '@/components/common/Dialog';
import ajax from '../static/js/ajax.js';
import compareJson from '../static/js/compareJson.js';

Vue.prototype.$ajax = ajax;
Vue.prototype.$compareJson = compareJson;

Vue.component('my-loading',Loading);
Vue.component('my-warn',Warn);
Vue.component('my-dialog',Dialog);
Vue.config.productionTip = true;

window.reqUrl = 'http://192.168.1.101:8082/interface/';
//window.reqUrl = '../interface/';

//[全局]进入前触发
router.beforeEach((to, from, next) => {
	if(to.meta.requireProp){
		if(!(store.state.adminInfo.hasOwnProperty('token') && store.state.adminInfo['token']))
			next({name: 'Login'});
		else{
			if(to.name == 'Administrator'){
				ajax({
					name: '验证管理员权限',
					url: window.reqUrl + 'ywms_admin.php',
					data: {
						handle: 'check',
						uid: store.state.adminInfo['id'],
						token: store.state.adminInfo['token'],
					},
					beforeSend () {
						store.commit('TOGGLE_LOADING',true);
					}
				}).then(res => {
					if(res.type == 'success'){
						next();
					}else{
						if(res.status == 1)
							setTimeout(() => {
								that.$store.commit('DEL_ADMININFO');
								router.push({name: 'Login'});
							},1000);
						store.commit('TOGGLE_WARNING',{
							msg: res.msg,
							visibility: true,
						});
					}
					store.commit('TOGGLE_LOADING',false);
				}).catch(status => {
					store.commit('TOGGLE_LOADING',false);
					store.commit('TOGGLE_WARNING',{
						msg: status,
						visibility: true,
					});
				});
			}else{
				next();
			}
		}
	}else{
		next(); // 确保一定要调用 next()
	}
});
//[全局]进入后触发
router.afterEach((to, from) => {
	store.commit('TOGGLE_LOADING',false);
});

/*注释*/
console.groupCollapsed('%c隐藏在角落的发光宝箱','color:gold;');
console.log('%c@author PZJ','font-size:16px;color:deepskyblue');
console.log('%c@version 1.0.0','font-size:16px;');
console.log('%c@date 2018-03-21','font-size:16px;');
console.log('仍有非常多的功能未完善，待续...');
console.groupEnd();

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
});
