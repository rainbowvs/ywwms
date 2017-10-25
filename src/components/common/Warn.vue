<template>
	<transition name="fade">
		<div class="warn" v-show="warning">
			<div class="container">
				<i class="fa fa-3x fa-warning"></i>
				<p>{{msg}}</p>
				<i id="close" class="fa fa-3x fa-close" @click="show($event)"></i>
			</div>
		</div>
	</transition>
</template>

<script>
	import store from '@/vuex/store';
	export default{
		data () {
			return {
				
			}
		},
		store,
		methods: {
			show (e) {
				this.$store.commit('TOGGLE_WARNING',{
					msg: '',
					state: false,
				});
			}
		},
		computed: {
			warning () {
				return this.$store.state.warnInfo['state'];
			},
			msg () {
				return this.$store.state.warnInfo['msg'];
			}
		}
	}
</script>

<style lang="scss" scoped>
	.warn{
		text-align: center;
		display: flex;
		flex-direction: column;
		justify-content: center;
		width: 100%;
		height: 100%;
		background: rgba(0,0,0,.5);
		position: fixed;
		z-index: 1000;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		&>.container{
			margin: 0 auto;
			width: 400px;
			height: 400px;
			background: rgba(255,255,255,.8);
			box-sizing: border-box;
			padding: 100px 50px;
			&>p{
				margin: 25px auto;
				height: 50px;
				color: #000;
				font-size: 20px;
			}
			&>#close{
				margin-top: 50px;
				&:hover{
					cursor: pointer;
					color: darkred;
				}
			}
		}
	}
	.fade-enter-active, .fade-leave-active {
		transition: opacity .2s
	}
	.fade-enter, .fade-leave-active {
		opacity: 0
	}
</style>