<template>
	<transition name="fade">
		<div class="editShop" v-show="confirming">
			<div class="container">
				<div class="content">
					<h1>修改商品</h1>
					<form action="" method="post">
						<ul>
							<li>
								<label for="name">名称：</label>
								<input type="text" id="name" />
							</li>
							<li>
								<label for="price">单价：</label>
								<input type="text" id="price" />
							</li>
							<li>
								<label for="purchased">已售：</label>
								<input type="text" id="purchased" />
							</li>
							<li>
								<label for="inventory">库存：</label>
								<input type="text" id="inventory" />
							</li>
							<li>
								<label for="color">颜色：</label>
								<input type="text" id="color" />
							</li>
							<li>
								<label for="poster">封面：</label>
								<input type="text" id="poster" />
							</li>
							<li>
								<label for="pic1">图一：</label>
								<input type="text" id="pic1" />
							</li>
							<li>
								<label for="pic2">图二：</label>
								<input type="text" id="pic2" />
							</li>
							<li>
								<label for="date">时间：</label>
								<input type="text" id="date" />
							</li>
						</ul>
					</form>
				</div>
				<!--<slot></slot>-->
				<div id="btn">
					<i id="no" class="fa fa-3x fa-close"></i>
					<i id="ok" class="fa fa-3x fa-check"></i>
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
				shops: [
					{name: '越王珠宝BG001 18K金共33分FG色 钻石女戒',price: 6896,inventory: 450,imgUrl: "http://rainbowvs.com/yuewang/img/hot_pro.jpg",checked: false},
					{name: '越王珠宝BG001 18K金共33分FG色 钻石女戒',price: 6896,inventory: 450,imgUrl: "http://rainbowvs.com/yuewang/img/hot_pro.jpg",checked: false},
				],
			}
		},
		store,
		methods: {
			
		},
		computed: {
			confirming () {
				return this.$store.state.confirmInfo['state'];
			},
			msg () {
				return this.$store.state.confirmInfo['msg'];
			}
		}
	}
</script>

<style lang="scss" scoped>
	.editShop{
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
			background: rgba(255,255,255,.8);
			box-sizing: border-box;
			padding: 50px 50px;
			&>.content{
				max-height: 500px;
				overflow-y: auto;
				&>h1{
					margin-bottom: 20px;
					text-align: center;
				}
				&>form{
					&>ul{
						&>li{
							padding: 0 50px;
							margin: 8px auto;
							line-height: 40px;
							overflow: hidden;
							&>label{
								cursor: pointer;
								float: left;
								margin-right: 10px;
							}
							&>input{
								width: 450px;
								float: left;
							    background-color: #fff;
							    background-image: none;
							    border: 1px solid #d8dce5;
							    box-sizing: border-box;
							    color: #5a5e66;
							    display: inline-block;
							    font-size: inherit;
							    height: 40px;
							    line-height: 1;
							    outline: none;
							    padding: 0 15px;
							    transition: border-color .2s cubic-bezier(.645,.045,.355,1);
							    &:focus{
							    	outline: none;
    								border-color: #324157;
							    }
							}
						}
					}
				}
			}
			&>#btn{
				margin-top: 15px;
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