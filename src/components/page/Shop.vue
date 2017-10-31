<template>
	<div class="shop">
		<h2>商品信息</h2>
		<div class="container">
			<div class="fit">
				<table>
					<tbody>
						<tr>
							<th>选择</th>
							<th>名称</th>
							<th>单价</th>
							<th>库存</th>
							<th>图片地址</th>
							<th>操作</th>
						</tr>
						<template v-if="shops.length==0">
							<tr>
								<td colspan="6">暂时没有找到商品信息</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="shop,index in shops">
								<td><input type="checkbox" v-model="shop.checked" /></td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="shop.name" />
									</template>
									<template v-else>
										{{shop.name}}
									</template>
								</td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="shop.price" />
									</template>
									<template v-else>
										{{shop.price}}
									</template>
								</td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="shop.inventory" />
									</template>
									<template v-else>
										{{shop.inventory}}
									</template>
								</td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="shop.imgUrl" />
									</template>
									<template v-else>
										{{shop.imgUrl}}
									</template>
								</td>
								<td>
									<template v-if="editable && flag==index">
										<button @click="edit($event,index)">保存</button>
									</template>
									<template v-else>
										<button @click="edit($event,index)">编辑</button>
									</template>
								</td>
							</tr>
						</template>
						<tr>
							<td><input type="checkbox" v-model="checkedAll" /></td>
							<td><button @click="remove">删除</button></td>
							<td><button @click="add">增加</button></td>
							<td colspan="3"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				shops: [
					{name: '越王珠宝BG001 18K金共33分FG色 钻石女戒',price: 6896,inventory: 450,imgUrl: "http://rainbowvs.com/yuewang/img/hot_pro.jpg",checked: false},
					{name: '越王珠宝BG001 18K金共33分FG色 钻石女戒',price: 6896,inventory: 450,imgUrl: "http://rainbowvs.com/yuewang/img/hot_pro.jpg",checked: false},
				],
				flag: null,
				editable: false,
			}
		},
		computed: {
			checkedAll: {
				get () {
					if(this.checkedCount == this.shops.length){
						if(this.checkedCount == 0)
							return false;
						else
							return true;
					}
					return false;
				},
				set (value) {
					this.shops.forEach((item) => {
						item.checked = value;
					});
					return value;
				}
			},
			checkedCount: {
				get () {
					let count = 0;
					this.shops.forEach((item) => {
						if(item.checked)
							count++;
					});
					return count;
				}
			}
		},
		methods: {
			add () {
				this.shops.push({
					name: '',
					price: '',
					inventory: '',
					imgUrl: '',
					checked: false
				});
				this.editable = true;
				this.flag = this.shops.length - 1;
			},
			edit (e,index) {
				this.editable = !this.editable;
				if(this.editable)
					e.target.innerText = '保存';
				else
					e.target.innerText = '编辑';
				this.flag = index;
			},
			remove () {
				let temp = [];
				this.shops.forEach((item) => {
					if(!item.checked)
						temp.push(item);
				});
				this.shops = temp;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.shop{
		width: 100%;
		&>h2{
			border-left: 4px solid #324157;
			padding-left: 10px;
			margin-bottom: 20px;
		}
		&>.container{
		    border: 1px solid #d1dbe5;
		    border-radius: 4px;
		    background-color: #fff;
		    overflow: hidden;
		    box-shadow: 0 2px 4px 0 rgba(0,0,0,.12), 0 0 6px 0 rgba(0,0,0,.04);
		    box-sizing: border-box;
		    padding: 20px;
			&>.fit{
				overflow-x: auto;
			    width: 100%;
			    max-width: 100%;
			    background-color: #fff;
			    font-size: 14px;
			    color: #1f2d3d;
				&>table{
					white-space: nowrap;
					width: 100%;
					text-align: center;
					vertical-align: middle;
					border-collapse: collapse;
					&>tbody{
						&>tr{
							&:hover{
								background-color: #eef1f6;;
							}
							&>th{
								height: 40px;
								background-color: #eef1f6;
								color: #000;
								border: 1px solid #dfe6ec;
								&:nth-child(1){
									width: 50px;
								}
							}
							&>td{
								padding: 0 30px;
								height: 40px;
								border: 1px solid #dfe6ec;
								&>input[type=text]{
									text-align: center;
									width: 100%;
									font-family: "microsoft yahei";
									font-size: 14px;
									background-color: transparent;
									border-bottom: 1px solid #20a0ff;
									&:focus{
										color: #20a0ff;
									}
								}
								&>button{
									width: 50px;
									height: 30px;
									cursor: pointer;
									background-color: #324157;
									color: #bfcbd9;;
									&:hover{
										color: #fff;
									}
									&:active{
										color: #20a0ff;
									}
								}
							}
						}
					}
				}
			}
		}
	}
</style>