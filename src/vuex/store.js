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
};

const mutations = {
	UPDATE_CURRENTPAGE (state,index) {
		state.currentPage = index;
		window.localStorage.setItem('currentPage',index);
	},
	TOGGLE_WARNING (state,opt) {
		state.warnInfo['visibility'] = opt.visibility;
		state.warnInfo['msg'] = opt.msg;
	},
	TOGGLE_LOADING (state,bool) {
		state.loading = bool;
	},
};

export default new Vuex.Store({
	state,
	mutations
});
