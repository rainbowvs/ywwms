import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
	currentPage: 0,
	warnInfo: {
		msg: '',
		state: false,
	},
	confirmInfo: {
		msg: '',
		state: false,
	},
	loading: false,
};

const mutations = {
	UPDATE_CURRENTPAGE (state,index) {
		state.currentPage = index;
		window.localStorage.setItem('currentPage',index);
	},
	TOGGLE_WARNING (state,opt) {
		state.warnInfo['msg'] = opt.msg;
		state.warnInfo['state'] = opt.state;
	},
	TOGGLE_CONFIRMING (state,opt) {
		state.confirmInfo['msg'] = opt.msg;
		state.confirmInfo['state'] = opt.state;
	},
	SET_CONFIRMING (state,bool) {
		state.confirmInfo['returnValue'] = bool;
	},
	TOGGLE_LOADING (state,bool) {
		state.loading = bool;
	}
};

export default new Vuex.Store({
	state,
	mutations
});
