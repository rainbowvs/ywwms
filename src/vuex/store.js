import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
	currentPage: 0,
	warnInfo: {
		msg: '',
		visibility: false,
	},
	dialogInfo: {
		msg: '',
		visibility: false,
	},
	loading: false,
	adminInfo: window.sessionStorage['adminInfo'] ? JSON.parse(window.sessionStorage['adminInfo']) : {},//用户信息
};

const mutations = {
	UPDATE_CURRENTPAGE (state,index) {
		state.currentPage = index;
		window.sessionStorage.setItem('currentPage',index);
	},
	TOGGLE_WARNING (state,opt) {
		state.warnInfo['visibility'] = opt.visibility;
		state.warnInfo['msg'] = opt.msg;
	},
	TOGGLE_LOADING (state,bool) {
		state.loading = bool;
	},
	SET_ADMININFO (state,obj) {
		state['adminInfo'] = obj['adminInfo'];
		window.sessionStorage.setItem('adminInfo',JSON.stringify(state.adminInfo));
	},
	DEL_ADMININFO (state,obj) {
		state['adminInfo'] = {};
		window.sessionStorage.setItem('adminInfo',JSON.stringify(state.adminInfo));
	},
};

export default new Vuex.Store({
	state,
	mutations
});
