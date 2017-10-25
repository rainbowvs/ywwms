<template>
	<div class="wrapper">
		<my-header></my-header>
		<my-sidebar></my-sidebar>
		<div class="content">
            <transition name="fade">
				<keep-alive>
	            	<router-view style="position:absolute;"></router-view>
	            </keep-alive>
            </transition>
        </div>
	</div>
</template>

<script>
	import Header from './Header';
	import Sidebar from './Sidebar';
	import store from '@/vuex/store';
//	import {ajax,formatData} from '../../../static/js/ajax';
	export default{
		data () {
			return {
				
			}
		},
		mounted () {
			
		},
		components: {
			'myHeader': Header,
			'mySidebar': Sidebar,
		}
	}
</script>

<style lang="scss" scoped>
	.wrapper{
		background: none repeat scroll 0 0 #f0f3f4;
		&>.content{
		    position: absolute;
		    left: 200px;
		    right: 0;
		    top: 66px;
		    bottom: 0;
		    margin: 25px 40px;
			/*padding: 25px 40px;*/
		    overflow-y: visible;
		    background-color: #f0f3f4;
		}
	}
	.fade-enter-active, .fade-leave-active {
		transition: opacity .2s
	}
	.fade-enter, .fade-leave-active {
		opacity: 0
	}
</style>