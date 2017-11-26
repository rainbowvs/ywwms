<template>
	<transition name="fade">
		<div class="edit" v-show="visible">
			<div class="container">
				<div class="header">
					<slot name="header"></slot>
				</div>
				<div class="body">
					<slot name="body"></slot>
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
				visible: false,
				data: null,
			}
		},
		methods: {
			close () {
				this.visible = false;
			},
			open (data) {
				this.visible = true;
				this.data = data;
			},
			ok () {
				this.$emit('sure',this.data);
			},
		},
	}
</script>

<style lang="scss" scoped>
	.edit{
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
			background: #fff;
			display: flex;
			flex-direction: column;
			justify-content: center;
			&>.header{
				padding: 20px 0;
			}
			&>.body{
				padding: 0 50px;
			}
			&>.footer{
				padding: 20px 0;
				display: flex;
				flex-direction: row;
				justify-content: space-around;
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