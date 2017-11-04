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
	editing: false,
};

const mutations = {
	UPDATE_CURRENTPAGE (state,index) {
		state.currentPage = index;
		window.localStorage.setItem('currentPage',index);
	},
	TOGGLE_WARNING (state,opt) {
		state.warnInfo['msg'] = opt.msg;
		state.warnInfo['visibility'] = opt.visibility;
	},
	TOGGLE_DIALOGING (state,opt) {
		state.dialogInfo['msg'] = opt.msg;
		state.dialogInfo['visibility'] = opt.visibility;
	},
	SET_DIALOGING (state,bool) {
		state.dialogInfo['returnValue'] = bool;
	},
	TOGGLE_LOADING (state,bool) {
		state.loading = bool;
	},
	SET_EDITING (state,bool) {
		state.editing = bool;
	},
};

export default new Vuex.Store({
	state,
	mutations
});
