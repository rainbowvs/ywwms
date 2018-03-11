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

//[全局]进入前触发
router.beforeEach((to, from, next) => {
//	store.commit('TOGGLE_LOADING',true);
	if(to.meta.requireProp){
		if(window.localStorage.getItem('yw_token')){
			next();
		}else{
			next({name: 'Login'});
		}
	}else{
		next(); // 确保一定要调用 next()
	}
});
//[全局]进入后触发
router.afterEach((to, from) => {
	store.commit('TOGGLE_LOADING',false);
});

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
});
