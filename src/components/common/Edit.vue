<template>
	<transition name="fade">
		<div class="edit" v-show="visible">
			<div class="mask"></div>
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
		position: fixed;
		z-index: 1000;
		top: 50%;
		left: 50%;
		margin-left: -325px;
		margin-top: -377px;
		width: 680px;
		height: 754px;
		display: table;
		text-align: center;
		vertical-align: middle;
		&>.mask{
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(0,0,0,.5);
			z-index: -1;
		}
		&>.container{
			display: table-cell;
			text-align: center;
			vertical-align: middle;
			&>.header{
				padding: 20px 0;
				background-color: #fff;
			}
			&>.body{
				max-height: 600px;
				padding: 0 50px;
				overflow-y: auto;
				overflow-x: hidden;
				background-color: #fff;
			}
			&>.footer{
				padding: 10px 0;
				overflow: hidden;
				background-color: #fff;
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