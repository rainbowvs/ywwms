<template>
	<div class="order">
		<h2>订单信息</h2>
		<div class="container">
			<div class="fit">
				<table>
					<tbody>
						<tr>
							<th>选择</th>
							<th>订单号</th>
							<th>总价</th>
							<th>状态</th>
							<th>创建时间</th>
							<th>操作</th>
						</tr>
						<template v-if="orders.length==0">
							<tr>
								<td colspan="6">暂时没有找到订单信息</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="order,index in orders">
								<td><input type="checkbox" v-model="order.checked" /></td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="order.id" />
									</template>
									<template v-else>
										{{order.id}}
									</template>
								</td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="order.totalPrice" />
									</template>
									<template v-else>
										{{order.totalPrice}}
									</template>
								</td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="order.state" />
									</template>
									<template v-else>
										{{order.state}}
									</template>
								</td>
								<td>{{order.cdate}}</td>
								<td><button @click="edit($event,index)">编辑</button></td>
							</tr>
						</template>
						<tr>
							<td><input type="checkbox" v-model="checkedAll" /></td>
							<td><button @click="remove">删除</button></td>
							<td colspan="4"></td>
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
				orders: [
					{id: 13800138000,totalPrice: 6869,state: '等待发货',cdate: '2017-10-13 9:40:13',checked: false},
					{id: 13800138000,totalPrice: 6869,state: '等待发货',cdate: '2017-10-13 9:40:13',checked: false},
				],
				flag: null,
				editable: false,
			}
		},
		computed: {
			checkedAll: {
				get () {
					if(this.checkedCount == this.orders.length){
						if(this.checkedCount == 0)
							return false;
						else
							return true;
					}
					return false;
				},
				set (value) {
					this.orders.forEach((item) => {
						item.checked = value;
					});
					return value;
				}
			},
			checkedCount: {
				get () {
					let count = 0;
					this.orders.forEach((item) => {
						if(item.checked)
							count++;
					});
					return count;
				}
			}
		},
		methods: {
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
				this.orders.forEach((item) => {
					if(!item.checked)
						temp.push(item);
				});
				this.orders = temp;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.order{
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
										background-color: #48576a;
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