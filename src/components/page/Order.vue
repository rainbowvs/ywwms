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
							<td colspan="7"></td>
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
		<my-edit ref="edit" @sure="eOk">
			<template v-if="type == 'edit'">
				<h1 slot="header">修改订单</h1>
			</template>
			<template v-else>
				<h1 slot="header">增加订单</h1>
			</template>
			<form slot="body">
				<ul>
					<li>
						<label for="oid">编号</label>
						<input type="text" id="oid" :value="current.oid" readonly disabled />
					</li>
					<li>
						<label for="state">状态</label>
						<input type="text" id="state" v-model="current.state" placeholder="订单状态" />
					</li>
					<li>
						<label for="user">账号</label>
						<input type="text" id="user" v-model="current.user" placeholder="下单账号" />
					</li>
					<li>
						<label for="name">收货人</label>
						<input type="text" id="name"  v-model="current.name" placeholder="收货人名称" />
					</li>
					<li>
						<label for="address">收货地址</label>
						<input type="text" id="address" v-model="current.address" placeholder="收货地址" />
					</li>
					<li>
						<label for="totalPrice">总价</label>
						<input type="text" id="totalPrice" v-model="current.totalPrice" placeholder="订单总金额" />
					</li>
					<li>
						<label for="cdate">时间</label>
						<input type="text" id="cdate" v-model="current.cdate" placeholder="订单创建时间" />
					</li>
				</ul>
			</form>
		</my-edit>
		<my-dialog ref="dialog" @sure="dOk"></my-dialog>
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
			submitPage (oldValue){
				this.currentPage = oldValue;
				this.getOrderInfo(oldValue);
			},
			toPrev (oldValue) {
				this.currentPage = oldValue;
				this.getOrderInfo(oldValue);
			},
			toNext (oldValue) {
				this.currentPage = oldValue;
				this.getOrderInfo(oldValue);
			},
			selectPage (page) {
				this.getOrderInfo(page);
			},
			eOk (index) {
				let that = this;
				if(that.type == 'edit'){
					if(that.$compareJson(that.orders[index],that.current)){
						that.$store.commit('TOGGLE_WARNING',{
							msg: '没做任何修改',
							visibility: true,
						});
					}else{
						//提交修改订单信息
						that.$ajax({
							url: "http://rainbowvs.com/yuewang/ywwms/interface/order.php",
							data: {
								handle: 'update',
								oid: that.current.oid,
								uid: that.current.uid,
								state: that.current.state,
								user: that.current.user,
								name: that.current.name,
								address: that.current.address,
								totalPrice: that.current.totalPrice,
								cdate: that.current.cdate,
								token: localStorage.getItem("yw_token"),
							},
						}).then(response => {
							if(response.type == 'success'){
								let temp = response.order;
								temp["checked"] = false;
								that.orders.splice(index,1,temp);
								that.$refs.edit.close();
							}else if(response.type == "error"){
								//服务器返回其他原因
								console.log(response.msg);
							}
							that.$store.commit('TOGGLE_WARNING',{
								msg: response.msg,
								visibility: true,
							});
						});
					}
				}
			},
			edit (e,index) {
				this.focus = index;
				this.current = JSON.parse(JSON.stringify(this.orders[this.focus]));
				this.type = 'edit';
				this.$refs.edit.open(this.focus);
			},
			remove () {
				if(this.orders.length != 0){
					this.$refs.dialog.open('确认要删除选中的商品?');
				}
			},
			dOk () {
				let that = this;
				let arr_id = [];
				that.orders.forEach((item) => {
					if(item.checked)
						arr_id.push(item.id);
				});
				if(arr_id.length != 0){
					that.$ajax({
						url: "http://rainbowvs.com/yuewang/ywwms/interface/order.php",
						data: {
							handle: 'del',
							ids: arr_id.join(","),
							token: localStorage.getItem("yw_token"),
						},
					}).then(response => {
						arr_id = [];
						console.log(response);
						if(response.type == 'success'){
							let temp = [];
							that.orders.forEach((item) => {
								if(!item.checked)
									temp.push(item);
							});
							that.orders = temp;
							that.$refs.dialog.close();
						}else if(response.type == "error"){
							//服务器返回其他原因
						}
						that.$store.commit('TOGGLE_WARNING',{
							msg: response.msg,
							visibility: true,
						});
					});
				}else{
					that.$store.commit('TOGGLE_WARNING',{
						msg: '没有选中任何商品',
						visibility: true,
					});
					that.$refs.dialog.close();
				}
			},
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
		&>.edit{
			.header{
				
			}
			.body{
				max-height: 500px;
				overflow-y: auto;
				&>form{
					&>ul{
						&>li{
							margin: 8px auto;
							line-height: 40px;
							overflow: hidden;
							&>label{
								line-height: 38px;
								box-sizing: border-box;
								border: 1px solid #d8dce5;
								border-right: 0;
								cursor: pointer;
								float: left;
								width: 100px;
								-webkit-user-select: none;
							}
							&>input{
								width: 450px;
								float: left;
							    background-color: #fff;
							    border: 1px solid #d8dce5;
							    box-sizing: border-box;
							    color: #000;
							    display: inline-block;
							    height: 40px;
							    line-height: 1;
							    outline: none;
							    padding: 0 15px;
							    transition: border-color .2s cubic-bezier(.645,.045,.355,1);
							    &#oid{
									cursor: not-allowed;
									-webkit-user-select: none;
									background-color: #dfe6ec;
									&:focus{
										border-color: #d8dce5;
									}
								}
							    &:focus{
							    	outline: none;
    								border-color: #324157;
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