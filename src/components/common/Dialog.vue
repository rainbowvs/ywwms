<template>
	<transition name="fade">
		<div class="dialog" v-show="dialoging">
			<div class="container">
				<i class="fa fa-3x fa-comment"></i>
				<p>{{msg}}</p>
				<div id="btns">
					<i id="no" class="fa fa-3x fa-close" @click="toggle($event)"></i>
					<i id="ok" class="fa fa-3x fa-check" @click="toggle($event)"></i>
				</div>
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
			toggle () {
				this.$store.commit('TOGGLE_DIALOGING',{
					msg: '',
					visibility: false,
				});
			}
		},
		computed: {
			dialoging () {
				return this.$store.state.dialogInfo['visibility'];
			},
			msg () {
				return this.$store.state.dialogInfo['msg'];
			}
		}
	}
</script>

<style lang="scss" scoped>
	.dialog{
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
				line-height: 50px;
				color: #000;
				font-size: 20px;
			}
			&>#btns{
				display: flex;
				flex-direction: row;
				justify-content: space-around;
				margin-top: 50px;
				&>#no{
					cursor: pointer;
					&:hover{
						color: darkred;
					}
				}
				&>#ok{
					cursor: pointer;
					&:hover{
						color: green;
					}
				}
			}
		}
	}
	.fade-enter-active, .fade-leave-active {
		transition: opacity .4s
	}
	.fade-enter, .fade-leave-active {
		opacity: 0
	}
</style>