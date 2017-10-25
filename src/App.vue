<template>
  <div id="app">
    <router-view></router-view>
    <my-warn></my-warn>
		<my-loading></my-loading>
  </div>
</template>

<script>
export default {
  name: 'app',
}
</script>

<style lang="scss" scoped>
	#app{
		width: 100%;
		height: 100%;
		background-color: #f0f3f4;
		min-height: 886px;
		height: 100%;
	}
</style>
