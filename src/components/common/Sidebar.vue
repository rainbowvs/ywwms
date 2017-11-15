<template>
	<div class="sidebar">
		<ul>
			<li v-for="item,index in items" @click="toggle($event,index)" :class="{active:index==current}">
				<router-link :to="item.path">
					<i :class="item.cn"></i>
					{{item.title}}
				</router-link>
			</li>
		</ul>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				items: [
					{cn: 'fa fa-home',title: '首页',path: '/'},
					{cn: 'fa fa-pencil-square-o',title: '订单',path: '/order'},
					{cn: 'fa fa-shopping-bag',title: '商品',path: '/shop'},
					{cn: 'fa fa-vcard',title: '管理员',path: '/administrator'},
					{cn: 'fa fa-user',title: '用户',path: '/user'},
				],
			}
		},
		methods: {
			toggle (e,index) {
				this.$store.commit('UPDATE_CURRENTPAGE',index);
			},
		},
		computed: {
			current () {
				let localData = window.localStorage.getItem('currentPage');
		    	if(this.$store.state.currentPage===0 && localData){
		    		this.$store.commit('UPDATE_CURRENTPAGE',localData);//同步操作
		    	}
		    	return this.$store.state.currentPage;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.sidebar{
		width: 200px;
	    position: fixed;
	    left: 0;
	    top: 66px;
	    bottom: 0;
	    background: #324157;
	    &>ul{
	    	min-height: 820px;
	    	height: 100%;
    	    background-color: #324157;
    	    border-radius: 2px;
		    position: relative;
		    padding-left: 0;
		    &>li{
		    	height: 56px;
			    line-height: 56px;
			    cursor: pointer;
			    position: relative;
			    box-sizing: border-box;
			    white-space: nowrap;
			    overflow: hidden;
			    transition: all 0.5s;
			    &:hover{
			    	background-color: #48576a;
			    }
			    &.active{
		    	    &>a,&>a>i{
		    	    	transition: all 0.5s;
		    	    	color: #20a0ff;
		    	    }
			    }
			    &>a{
			    	width: 100%;
			    	font-size: 16px;
			    	display: inline-block;
			    	color: #bfcbd9;
			    	&>i{
			    		padding-left: 20px;
			    		text-align: center;
			    		width: 50px;
			    		line-height: 56px;
			    		color: #bfcbd9;
			    		font-size: 16px;
			    	}
			    }
		    }
	    }
	}
</style>