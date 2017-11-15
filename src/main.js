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

Vue.prototype.$ajax = ajax;

Vue.component('my-loading',Loading);
Vue.component('my-warn',Warn);
Vue.component('my-dialog',Dialog);
Vue.config.productionTip = true;

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
});
