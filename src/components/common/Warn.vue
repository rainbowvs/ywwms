<template>
	<transition name="fade">
		<div class="warn" v-show="warning">
			<div class="container">
				<div class="header">
					<i class="fa fa-3x fa-warning"></i>
				</div>
				<div class="body">
					<p>{{msg}}</p>
				</div>
				<div class="footer">
					<i id="close" class="fa fa-3x fa-close" @click="show($event)"></i>
				</div>
			</div>
		</div>
	</transition>
</template>

<script>
	export default{
		data () {
			return {
				
			}
		},
		methods: {
			show (e) {
				this.$store.commit('TOGGLE_WARNING',{
					msg: '',
					visibility: false,
				});
			}
		},
		computed: {
			warning () {
				return this.$store.state.warnInfo['visibility'];
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
			width: 500px;
			height: 400px;
			top: 50%;
			left: 50%;
			margin-left: -250px;
			margin-top: -200px;
			background: rgba(255,255,255,.8);
			box-sizing: border-box;
			padding: 0 50px;
			position: absolute;
			display: table;
			text-align: center;
			vertical-align: middle;
			&>.header{
				margin-top: 50px;
			}
			&>.body{
				&>p{
					line-height: 200px;
					font-size: 20px;
				}
			}
			&>.footer{
				text-align: center;
				&>#close{
					cursor: pointer;
					&:hover{
						color: darkred;
					}
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