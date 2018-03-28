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
							<th>状态</th>
							<th>收货人</th>
							<th>收货人手机</th>
							<th>收货地址</th>
							<th>总价</th>
							<th>买家留言</th>
							<th>创建时间</th>
							<th>操作</th>
						</tr>
						<template v-if="orders.length==0">
							<tr>
								<td colspan="10">暂时没有找到订单信息</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="order,index in orders">
								<td><input type="checkbox" v-model="order.checked" /></td>
								<td>{{order.oid}}</td>
								<td>
									<template v-if="order.state==0">待付款</template>
									<template v-else-if="order.state==1">待发货</template>
									<template v-else-if="order.state==2">待收货</template>
									<template v-else-if="order.state==3">待评价</template>
									<template v-else-if="order.state==4">交易完成</template>
								</td>
								<td>{{order.addressName}}</td>
								<td>{{order.addressPhone}}</td>
								<td>{{order.addressContent}}</td>
								<td>￥{{order.totalPrice}}</td>
								<td>
									<template v-if="order.remark==''">无</template>
									<template v-else>{{order.remark}}</template>
								</td>
								<td>{{order.cdate}}</td>
								<td>
									<button v-if="order.state == 1" @click="send($event,index)">发货</button>
									<button @click="edit($event,index)">编辑</button>
								</td>
							</tr>
						</template>
						<tr>
							<td><input type="checkbox" v-model="checkedAll" /></td>
							<td><button @click="remove">删除</button></td>
							<td colspan="8"></td>
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
				<ul class="baseList">
					<li>
						<label for="oid">订单编号</label>
						<input type="text" id="oid" :value="current.oid" title="订单编号由算法生成唯一，无法随意修改" readonly disabled />
					</li>
					<li>
						<label for="state">状态</label>
						<input type="text" id="state" v-model="current.state" title="输入对应数字，待付款=>0,待发货=>1,待收货=>2,交易完成=>3" placeholder="输入对应数字，待付款=>0,待发货=>1,待收货=>2,交易完成=>3" maxlength="1" />
					</li>
					<li>
						<label for="addressName">收货人</label>
						<input type="text" id="addressName" v-model="current.addressName" title="50个字符以内" placeholder="50个字符以内" maxlength="50" />
					</li>
					<li>
						<label for="addressPhone">收货人手机</label>
						<input type="text" id="addressPhone" v-model="current.addressPhone" title="输入11位手机号码" placeholder="输入11位手机号码" maxlength="11" />
					</li>
					<li>
						<label for="addressContent">收货地址</label>
						<input type="text" id="addressContent" v-model="current.addressContent" title="80个字符以内" placeholder="80个字符以内" maxlength="80" />
					</li>
					<li>
						<label for="totalPrice">总价</label>
						<input type="text" id="totalPrice" v-model="current.totalPrice" title="订单总金额由商品价格和数量决定，无法随意修改" placeholder="订单总金额由商品价格决定，无法随意修改" readonly disabled />
					</li>
					<li>
						<label for="remark">买家留言</label>
						<input type="text" id="remark" v-model="current.remark" title="可不填，150个字符以内" placeholder="可不填，150个字符以内" maxlength="150" />
					</li>
				</ul>
				<ul class="shopList">
					<li v-for="v,i in current.shops">
						<h2>
							<span>商品{{i+1}}</span>
							<em><button @click.prevent="removeShop(i)">移除</button></em>
						</h2>
						<p><span>编号</span><em v-text="v.id"></em></p>
						<p><span>名称</span><em v-text="v.name"></em></p>
						<p><span>数量</span><em v-text="v.amount"></em></p>
						<p><span>单价</span><em v-text="`￥${v.price}`"></em></p>
						<p v-if="v.size"><span>圈口</span><em v-text="v.size"></em></p>
						<p v-if="v.length"><span>链长</span><em v-text="v.length"></em></p>
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
				removeItems: [],
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
			checkState (state) {
				if(state == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写订单状态',
						visibility: true,
					});
					return false;
				}else if(!(/[0-4]{1}/g.test(state))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '订单状态范围0~4的整数',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkPhone (phone) {
				if(phone == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写手机号码',
						visibility: true,
					});
					return false;
				}else if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(phone)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '手机不得包含敏感字符',
						visibility: true,
					});
					return false;
				}else if(!(/^1[3|4|5|8]\d{9}$/.test(phone))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请输入正确的手机号码',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkName (name) {
				if(name == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写收货人名称',
						visibility: true,
					});
					return false;
				}else if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(name)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '收货人名称不得包含敏感字符',
						visibility: true,
					});
					return false;
				}else if(name.length >= 20){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '收货人名称长度不得超过20个字符',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkAddress (address) {
				if(address == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写地址',
						visibility: true,
					});
					return false;
				}else if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(address)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '地址不得包含敏感字符',
						visibility: true,
					});
					return false;
				}else if(address.length >= 80){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '地址长度不得超过80个字符',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkRemark (remark) {
				if(remark.length >= 150){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '买家留言长度不得超过150个字符',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			removeShop (index) {
				//移除订单中的某一件商品
				let tempPrice = parseFloat(this.current.shops[index].price * this.current.shops[index].amount).toFixed(2);
				this.current.totalPrice = parseFloat(this.current.totalPrice - tempPrice).toFixed(2);
				this.removeItems.push(this.current.shops.splice(index,1)[0]);
			},
			send (e,index) {
				//点击发货按钮事件
				let that = this;
				console.log(index)
				that.$ajax({
					name: '修改订单',
					url: window.reqUrl + 'ywms_order.php',
					data: {
						handle: 'send',
						uid: that.$store.state.adminInfo['id'],
						token: that.$store.state.adminInfo['token'],
						oid: that.orders[index].oid,
					},
					beforeSend () {
						that.$store.commit('TOGGLE_LOADING',true);
					}
				}).then(res => {
					if(res.type == 'success'){
						that.orders[index].state = '2';
						that.$store.commit('TOGGLE_WARNING',{
							msg: res.msg,
							visibility: true,
						});
						that.$refs.edit.close();
					}else{
						if(res.status == 1)
							setTimeout(() => {
								that.$store.commit('DEL_ADMININFO');
								that.$router.push({name: 'Login'});
							},1000);
						that.$store.commit('TOGGLE_WARNING',{
							msg: res.msg,
							visibility: true,
						});
					}
					that.$store.commit('TOGGLE_LOADING',false);
				}).catch(status => {
					that.$store.commit('TOGGLE_LOADING',false);
					that.$store.commit('TOGGLE_WARNING',{
						msg: status,
						visibility: true,
					});
				});
			},
			getOrderInfo (currentPage) {
				let that = this;
				that.$ajax({
					name: '获取所有订单列表',
					url: window.reqUrl + 'ywms_order.php',
					data: {
						handle: 'get',
						uid: that.$store.state.adminInfo['id'],
						token: that.$store.state.adminInfo['token'],
						page: currentPage,
					},
					beforeSend () {
						that.$store.commit('TOGGLE_LOADING',true);
					}
				}).then(res => {
					if(res.type == 'success'){
						that.orders = [];
						res.orders.forEach((v,i) => {
							that.orders.push({
								addressContent: v.addressContent,
								addressName: v.addressName,
								addressPhone: v.addressPhone,
								cdate: v.cdate,
								oid: v.oid,
								remark: v.remark,
								state: v.state,
								totalPrice: v.totalPrice,
								shops: v.shops,
								checked: false,
							});
						});
						that.totalPage = res.totalPage;
					}else{
						if(res.status == 1)
							setTimeout(() => {
								that.$store.commit('DEL_ADMININFO');
								that.$router.push({name: 'Login'});
							},1000);
						that.$store.commit('TOGGLE_WARNING',{
							msg: res.msg,
							visibility: true,
						});
					}
					that.$store.commit('TOGGLE_LOADING',false);
				}).catch(status => {
					that.$store.commit('TOGGLE_LOADING',false);
					that.$store.commit('TOGGLE_WARNING',{
						msg: status,
						visibility: true,
					});
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
				if(!(
					that.checkState(that.current.state) && 
					that.checkName(that.current.addressName) && 
					that.checkPhone(that.current.addressPhone) &&
					that.checkAddress(that.current.addressContent) &&
					that.checkRemark(that.current.remark)
				))
					return false;
				if(that.type == 'edit'){
					if(that.$compareJson(that.orders[index],that.current)){
						that.$store.commit('TOGGLE_WARNING',{
							msg: '没做任何修改',
							visibility: true,
						});
					}else{
						let odIds = [];
						that.removeItems.forEach((v,i) => {
							odIds.push(v.od_id);
						});
						odIds = odIds.join(',');
						
						//提交修改订单信息
						that.$ajax({
							name: '修改订单',
							url: window.reqUrl + 'ywms_order.php',
							data: {
								handle: 'update',
								uid: that.$store.state.adminInfo['id'],
								token: that.$store.state.adminInfo['token'],
								oid: that.current.oid,
								addressName: that.current.addressName,
								addressPhone: that.current.addressPhone,
								addressContent: that.current.addressContent,
								totalPrice: that.current.totalPrice,
								state: that.current.state,
								remark: that.current.remark,
								odIds: odIds,
								cdate: that.current.cdate,
							},
							beforeSend () {
								that.$store.commit('TOGGLE_LOADING',true);
							}
						}).then(res => {
							if(res.type == 'success'){
								that.orders.splice(index,1,that.current);
								that.$store.commit('TOGGLE_WARNING',{
									msg: res.msg,
									visibility: true,
								});
								that.$refs.edit.close();
							}else{
								if(res.status == 1)
									setTimeout(() => {
										that.$store.commit('DEL_ADMININFO');
										that.$router.push({name: 'Login'});
									},1000);
								that.$store.commit('TOGGLE_WARNING',{
									msg: res.msg,
									visibility: true,
								});
							}
							that.$store.commit('TOGGLE_LOADING',false);
						}).catch(status => {
							that.$store.commit('TOGGLE_LOADING',false);
							that.$store.commit('TOGGLE_WARNING',{
								msg: status,
								visibility: true,
							});
						});
					}
				}
			},
			edit (e,index) {
				this.removeItems = [];
				this.focus = index;
				this.current = JSON.parse(JSON.stringify(this.orders[this.focus]));
				this.type = 'edit';
				this.$refs.edit.open(this.focus);
			},
			remove () {
				if(this.orders.length != 0){
					this.$refs.dialog.open('确认要删除选中的订单?');
				}
			},
			dOk () {
				let that = this;
				let arr_id = [];
				that.orders.forEach((item) => {
					if(item.checked)
						arr_id.push(item.oid);
				});
				if(arr_id.length != 0){
					that.$ajax({
						name: '删除指定订单',
						url: window.reqUrl + 'ywms_order.php',
						data: {
							handle: 'del',
							uid: that.$store.state.adminInfo['id'],
							token: that.$store.state.adminInfo['token'],
							oids: arr_id.join(","),
						},
						beforeSend () {
							that.$store.commit('TOGGLE_LOADING',true);
						}
					}).then(res => {
						arr_id = [];
						if(res.type == 'success'){
							let temp = [];
							that.orders.forEach((item) => {
								if(!item.checked)
									temp.push(item);
							});
							that.orders = temp;
							that.$refs.dialog.close();
							that.$store.commit('TOGGLE_WARNING',{
								msg: res.msg,
								visibility: true,
							});
						}else{
							if(res.status == 1)
								setTimeout(() => {
									that.$store.commit('DEL_ADMININFO');
									that.$router.push({name: 'Login'});
								},1000);
							that.$store.commit('TOGGLE_WARNING',{
								msg: res.msg,
								visibility: true,
							});
						}
						that.$store.commit('TOGGLE_LOADING',false);
					}).catch(status => {
						that.$store.commit('TOGGLE_LOADING',false);
						that.$store.commit('TOGGLE_WARNING',{
							msg: status,
							visibility: true,
						});
					});
				}else{
					that.$store.commit('TOGGLE_WARNING',{
						msg: '没有选中任何订单',
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
								padding: 0 15px;
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
					&>.baseList{
						overflow: hidden;
						max-width: 550px;
						&>li{
							margin: 8px auto;
							line-height: 40px;
							overflow: hidden;
							&>label{
								width: 100px;
								line-height: 38px;
								box-sizing: border-box;
								border: 1px solid #d8dce5;
								border-right: 0;
								cursor: pointer;
								float: left;
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
							    &#oid,&#totalPrice{
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
					&>.shopList{
						max-width: 550px;
						&>li{
							box-sizing: border-box;
							margin-bottom: 10px;
							border: 1px solid #d8dce5;
							&>h2{
								line-height: 40px;
								font-size: 16px;
								font-weight: normal;
								overflow: hidden;
								&>span{
									display: block;
									float: left;
									width: 30%;
									border-right: 1px solid #d8dce5;
									box-sizing: border-box;
								}
								&>em{
									font-style: normal;
									display: block;
									float: left;
									width: 70%;
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
							&>p{
								box-sizing: border-box;
								border-top: 1px solid #d8dce5;
								overflow: hidden;
								line-height: 30px;
								&>span{
									display: block;
									float: left;
									width: 30%;
									border-right: 1px solid #d8dce5;
									box-sizing: border-box;
								}
								&>em{
									font-style: normal;
									display: block;
									float: left;
									width: 70%;
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