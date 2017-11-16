<template>
	<div class="order">
		<h2>订单信息</h2>
		<div class="container">
			<div class="fit">
				<table>
					<tbody>
						<tr>
							<th>选择</th>
							<th>编号</th>
							<th>状态</th>
							<th>账号</th>
							<th>收货人</th>
							<th>收货地址</th>
							<th>总价</th>
							<th>创建时间</th>
							<th>操作</th>
						</tr>
						<template v-if="orders.length==0">
							<tr>
								<td colspan="9">暂时没有找到订单信息</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="order,index in orders">
								<td><input type="checkbox" v-model="order.checked" /></td>
								<td>{{order.oid}}</td>
								<td>{{order.state}}</td>
								<td>{{order.user}}</td>
								<td>{{order.name}}</td>
								<td>{{order.address}}</td>
								<td>{{order.totalPrice}}</td>
								<td>{{order.cdate}}</td>
								<td><button @click="edit($event,index)">编辑</button></td>
							</tr>
						</template>
						<tr>
							<td><input type="checkbox" v-model="checkedAll" /></td>
							<td><button @click="remove">删除</button></td>
							<td><button @click="add">增加</button></td>
							<td colspan="6"></td>
						</tr>
					</tbody>
				</table>
				<my-pagination
					:current="currentPage"
					:total="totalPage"
					:show="showPage"
					@prev="toPrev"
					@next="toNext"
					@selectpage="selectPage"
					@submitpage="submitPage">
				</my-pagination>
			</div>
		</div>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				current: {},
				orders: [],
				focus: null,
				type: '',
				totalPage: 1,//总页数
				currentPage: 1,//当前显示第几页
				showPage: 5,//显示的页数[只能输入奇数,偶数最后一页无法显示]
			}
		},
		created () {
			this.getOrderInfo(1);
		},
		components: {
			'my-edit': () => import('@/components/common/Edit'),
			'my-pagination': () => import('@/components/common/Pagination'),
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
			getOrderInfo (currentPage) {
				let that = this;
				that.$ajax({
					url: "http://rainbowvs.com/yuewang/ywwms/interface/order.php",
					overtime: 3000,
					data: {
						handle: 'get',
						page: currentPage,//商品分页
						token: localStorage.getItem("yw_token"),
					},
					beforeSend () {
						
					},
					complete (msg) {
						console.log(msg);
					},
				}).then(response => {
					if(response.type == 'success'){
						that.orders = [];
						for(let i in response.orders)
							that.orders.push({
								oid: response.orders[i].oid,
								uid: response.orders[i].uid,
								state: response.orders[i].state,
								user: response.orders[i].user,
								name: response.orders[i].name,
								address: response.orders[i].address,
								totalPrice: response.orders[i].totalPrice,
								cdate: response.orders[i].cdate,
								checked: false
							});
						that.totalPage = response.totalPage;
					}else if(response.type == "error"){
						that.$store.commit('TOGGLE_WARNING',{
							msg: response.msg,
							visibility: true,
						});
					}
				}).catch(status => {
					console.log(status);
				});
			},
			remove () {
				
			},
			add () {
				
			},
			edit (e,page) {
				
			},
			toPrev () {},
			toNext () {},
			selectPage () {},
			submitPage () {},
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
	input::-webkit-input-placeholder{
	    color: #aaa;
	}
</style>