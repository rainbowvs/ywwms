<template>
	<transition name="fade">
		<div class="loading" v-show="loading">
			<div class="container">
				<i class="fa fa-refresh fa-spin fa-4x fa-fw margin-top"></i>
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
		computed: {
			loading () {
				return this.$store.state.loading;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.loading{
		display: flex;
		flex-direction: column;
		justify-content: center;
		z-index: 10000;
		width: 100%;
		height: 100%;
		position: fixed;
		top: 0;
		right: 0;
		left: 0;
		bottom: 0;
		background-color: rgba(0,0,0,.5);
		&>.container{
			display: flex;
			flex-direction: column;
			justify-content: center;
			margin: 0 auto;
			width: 200px;
			height: 200px;
			line-height: 200px;
			background-color: rgba(255,255,255,.8);
			&>i{
				display: block;
				margin: 0 auto;
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