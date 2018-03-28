<template>
	<transition name="fade">
		<div class="dialog" v-show="visibility">
			<div class="container">
				<div class="header">
					<i class="fa fa-3x fa-comment"></i>
				</div>
				<div class="body">
					<p>{{msg}}</p>
				</div>
				<div class="footer">
					<i id="no" class="fa fa-3x fa-close" @click="close"></i>
					<i id="ok" class="fa fa-3x fa-check" @click="ok"></i>
				</div>
			</div>
		</div>
	</transition>
</template>

<script>
	export default{
		data () {
			return {
				visibility: false,
				msg: '',
			}
		},
		methods: {
			close () {
				this.visibility = false;
			},
			open (msg) {
				this.visibility = true;
				this.msg = msg;
				
			},
			ok () {
				this.$emit('sure',true);
			},
		},
	}
</script>

<style lang="scss" scoped>
	.dialog{
		width: 100%;
		height: 100%;
		background: rgba(0,0,0,.5);
		position: fixed;
		z-index: 100;
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
				overflow: hidden;
				&>#no{
					width: 50%;
					float: left;
					cursor: pointer;
					&:hover{
						color: darkred;
					}
				}
				&>#ok{
					width: 50%;
					float: left;
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