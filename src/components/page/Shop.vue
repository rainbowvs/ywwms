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
							<th>已售</th>
							<th>库存</th>
							<th>颜色</th>
							<th>封面图片地址</th>
							<th>介绍图片地址1</th>
							<th>介绍图片地址2</th>
							<th>创建时间</th>
							<th>操作</th>
						</tr>
						<template v-if="shops.length==0">
							<tr>
								<td colspan="11">暂时没有找到商品信息</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="shop,index in shops">
								<td><input type="checkbox" v-model="shop.checked" /></td>
								<td>{{shop.name}}</td>
								<td>{{shop.price}}</td>
								<td>{{shop.purchased}}</td>
								<td>{{shop.inventory}}</td>
								<td>{{shop.color}}</td>
								<td>{{shop.poster}}</td>
								<td>{{shop.pic1}}</td>
								<td>{{shop.pic2}}</td>
								<td>{{shop.cdate}}</td>
								<td><button @click="edit($event,index)">编辑</button></td>
							</tr>
						</template>
						<tr>
							<td><input type="checkbox" v-model="checkedAll" /></td>
							<td><button @click="remove">删除</button></td>
							<td><button @click="add">增加</button></td>
							<td colspan="8"></td>
						</tr>
					</tbody>
				</table>
				<div class="pagination">
					<ul>
						<li class="prev" @click="toPrev($event)">&lt;</li>
						<li v-for="page in pages" :class="{active:currentPage==page}" @click="selectPage($event,page)">{{page}}</li>
						<li class="dot">...</li>
						<li class="next" @click="toNext($event)">&gt;</li>
						<template v-if="currentPage<totalPage">
							<p>共 {{totalPage}} 页，到第 <input ref="inputPage" type="number" :value="toPage" :max="totalPage" min="1"/> 页</p>
							<button @click="submitPage">确定</button>
						</template>
						<template v-else>
							<p>共 {{totalPage}} 页，到第 <input ref="inputPage" type="number" :value="currentPage" :max="totalPage" min="1"/> 页</p>
							<button @click="submitPage">确定</button>
						</template>
					</ul>
				</div>
			</div>
		</div>
		<my-edit ref="edit" @sure="eOk">
			<template v-if="type == 'edit'">
				<h1 slot="header">修改商品</h1>
			</template>
			<template v-else>
				<h1 slot="header">增加商品</h1>
			</template>
			<form slot="body">
				<ul>
					<li>
						<label for="name">名称</label>
						<input type="text" id="name" v-model="current.name" placeholder="商品名称" />
					</li>
					<li>
						<label for="price">单价</label>
						<input type="text" id="price" v-model="current.price" placeholder="商品单价" />
					</li>
					<li>
						<label for="purchased">已售</label>
						<input type="text" id="purchased" v-model="current.purchased" placeholder="已售出的商品数量" />
					</li>
					<li>
						<label for="inventory">库存</label>
						<input type="text" id="inventory"  v-model="current.inventory" placeholder="商品库存数量" />
					</li>
					<li>
						<label for="color">颜色</label>
						<input type="text" id="color" v-model="current.color" placeholder="商品颜色" />
					</li>
					<li>
						<label for="poster">封面</label>
						<input type="text" id="poster" v-model="current.poster" placeholder="商品封面图片地址" />
					</li>
					<li>
						<label for="pic1">图一</label>
						<input type="text" id="pic1" v-model="current.pic1" placeholder="商品介绍图片地址一" />
					</li>
					<li>
						<label for="pic2">图二</label>
						<input type="text" id="pic2" v-model="current.pic2" placeholder="商品介绍图片地址二" />
					</li>
					<li>
						<label for="cdate">时间</label>
						<input type="text" id="cdate" v-model="current.cdate" placeholder="商品创建时间" />
					</li>
				</ul>
			</form>
		</my-edit>
		<my-dialog ref="dialog" @sure="dOk"></my-dialog>
	</div>
</template>

<script>
	import store from '@/vuex/store';
	import ajax from '../../../static/js/ajax.js';
	import Edit from '@/components/common/Edit'
	export default{
		data () {
			return {
				current: {},
				shops: [],
				focus: null,
				currentPage: 1,
				totalPage: 1,
				pageSize: 5,
				type: '',
			}
		},
		store,
		created () {
			let that = this;
			//拉取商品信息
			ajax({
				url: "http://rainbowvs.com/yuewang/ywwms/interface/shop.php",
				overtime: 3000,
				data: {
					handle: 'get',
					page: 1,//商品分页
					token: localStorage.getItem("yw_token"),
				},
				complete (msg){
					console.log(msg);
				}
			}).then(response => {
				if(response.type == 'success'){
					for(let i in response.shops)
						that.shops.push({
							id: response.shops[i].id,
							typeId: response.shops[i].typeId,
							name: response.shops[i].name,
							price: response.shops[i].price,
							purchased: response.shops[i].purchased,
							inventory: response.shops[i].inventory,
							color: response.shops[i].color,
							poster: response.shops[i].poster,
							pic1: response.shops[i].pic1,
							pic2: response.shops[i].pic2,
							cdate: response.shops[i].cdate,
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
		components: {
			'my-edit': Edit,
		},
		computed: {
			toPage () {
				return +this.currentPage+1;
			},
			pages () {
				let left = 1,
					right = this.totalPage,
					movePoint = Math.ceil(this.pageSize / 2),
					pages = [];
				if (this.currentPage > movePoint && this.currentPage < this.totalPage - movePoint + 1) {
					left = this.pageSize % 2 === 0 ? this.currentPage - movePoint : this.currentPage - movePoint + 1;
					right = this.currentPage + movePoint - 1;
				} else if (this.currentPage <= movePoint) {
					left = 1;
					right = this.pageSize;
				} else {
					left = this.totalPage - this.pageSize + 1;
					right = this.totalPage;
				}

				while (left <= right) {
					pages.push(left);
					left++;
				}
				return pages;
			},
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
			submitPage(){
				let that = this;
				var oldValue = that.$refs.inputPage.value;
				if(oldValue < 1 || oldValue > that.totalPage || (oldValue == that.currentPage))
					return false;
				else{
					console.log(that.currentPage,oldValue);
//					that.currentPage = oldValue;
//					ajax({
//						url: "http://rainbowvs.com/yuewang/ywwms/interface/shop.php",
//						overtime: 3000,
//						data: {
//							handle: 'get',
//							page: that.currentPage,//商品分页
//							token: localStorage.getItem("yw_token"),
//						},
//						complete (msg){
//							console.log(msg);
//						}
//					}).then(response => {
//						if(response.type == 'success'){
//							that.shops = [];
//							for(let i in response.shops)
//								that.shops.push({
//									id: response.shops[i].id,
//									typeId: response.shops[i].typeId,
//									name: response.shops[i].name,
//									price: response.shops[i].price,
//									purchased: response.shops[i].purchased,
//									inventory: response.shops[i].inventory,
//									color: response.shops[i].color,
//									poster: response.shops[i].poster,
//									pic1: response.shops[i].pic1,
//									pic2: response.shops[i].pic2,
//									cdate: response.shops[i].cdate,
//									checked: false
//								});
//							that.totalPage = response.totalPage;
//						}else if(response.type == "error"){
//							that.$store.commit('TOGGLE_WARNING',{
//								msg: response.msg,
//								visibility: true,
//							});
//						}
//					}).catch(status => {
//						console.log(status);
//					});
				}
			},
			toPrev(e){
				let that = this;
				if(that.currentPage > 1){
					that.currentPage--;
					ajax({
						url: "http://rainbowvs.com/yuewang/ywwms/interface/shop.php",
						overtime: 3000,
						data: {
							handle: 'get',
							page: that.currentPage,//商品分页
							token: localStorage.getItem("yw_token"),
						},
						complete (msg){
							console.log(msg);
						}
					}).then(response => {
						if(response.type == 'success'){
							that.shops = [];
							for(let i in response.shops)
								that.shops.push({
									id: response.shops[i].id,
									typeId: response.shops[i].typeId,
									name: response.shops[i].name,
									price: response.shops[i].price,
									purchased: response.shops[i].purchased,
									inventory: response.shops[i].inventory,
									color: response.shops[i].color,
									poster: response.shops[i].poster,
									pic1: response.shops[i].pic1,
									pic2: response.shops[i].pic2,
									cdate: response.shops[i].cdate,
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
				}
			},
			toNext(e){
				let that = this;
				if(that.currentPage < that.totalPage){
					that.currentPage++;
					ajax({
						url: "http://rainbowvs.com/yuewang/ywwms/interface/shop.php",
						overtime: 3000,
						data: {
							handle: 'get',
							page: that.currentPage,//商品分页
							token: localStorage.getItem("yw_token"),
						},
						complete (msg){
							console.log(msg);
						}
					}).then(response => {
						if(response.type == 'success'){
							that.shops = [];
							for(let i in response.shops)
								that.shops.push({
									id: response.shops[i].id,
									typeId: response.shops[i].typeId,
									name: response.shops[i].name,
									price: response.shops[i].price,
									purchased: response.shops[i].purchased,
									inventory: response.shops[i].inventory,
									color: response.shops[i].color,
									poster: response.shops[i].poster,
									pic1: response.shops[i].pic1,
									pic2: response.shops[i].pic2,
									cdate: response.shops[i].cdate,
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
				}
			},
			selectPage(e,page){
				let that = this;
				if(page > that.totalPage)
					return false;
				else if(page < 1)
					return false;
				if(that.currentPage != page)
					that.currentPage = page;
				ajax({
					url: "http://rainbowvs.com/yuewang/ywwms/interface/shop.php",
					overtime: 3000,
					data: {
						handle: 'get',
						page: page,//商品分页
						token: localStorage.getItem("yw_token"),
					},
					complete (msg){
						console.log(msg);
					}
				}).then(response => {
					if(response.type == 'success'){
						that.shops = [];
						for(let i in response.shops)
							that.shops.push({
								id: response.shops[i].id,
								typeId: response.shops[i].typeId,
								name: response.shops[i].name,
								price: response.shops[i].price,
								purchased: response.shops[i].purchased,
								inventory: response.shops[i].inventory,
								color: response.shops[i].color,
								poster: response.shops[i].poster,
								pic1: response.shops[i].pic1,
								pic2: response.shops[i].pic2,
								cdate: response.shops[i].cdate,
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
			eOk (type) {
				let that = this;
				if(type == 'add'){
					//提交新增商品信息
					ajax({
						url: "http://rainbowvs.com/yuewang/ywwms/interface/shop.php",
						overtime: 3000,
						data: {
							handle: 'set',
							typeId: that.current.typeId,
							name: that.current.name,
							price: that.current.price,
							purchased: that.current.purchased,
							inventory: that.current.inventory,
							color: that.current.color,
							poster: that.current.poster,
							pic1: that.current.pic1,
							pic2: that.current.pic2,
							token: localStorage.getItem("yw_token"),
						},
						complete (msg){
							console.log(msg);
						}
					}).then(response => {
						console.log(response);
						if(response.type == 'success'){
							that.shops.push({
								id: response.shop.id,
								typeId: response.shop.typeId,
								name: response.shop.name,
								price: response.shop.price,
								purchased: response.shop.purchased,
								inventory: response.shop.inventory,
								color: response.shop.color,
								poster: response.shop.poster,
								pic1: response.shop.pic1,
								pic2: response.shop.pic2,
								cdate: response.shop.cdate,
								checked: false
							});
							that.$refs.edit.close();
						}else if(response.type == "error"){
							//服务器返回其他原因
						}
						that.$store.commit('TOGGLE_WARNING',{
							msg: response.msg,
							visibility: true,
						});
					}).catch(status => {
						console.log(status);
					});
				}else if(type == 'edit'){
					//提交修改商品信息
					ajax({
						url: "http://rainbowvs.com/yuewang/ywwms/interface/shop.php",
						overtime: 3000,
						data: {
							handle: 'update',
							id: that.current.id,
							typeId: that.current.typeId,
							name: that.current.name,
							price: that.current.price,
							purchased: that.current.purchased,
							inventory: that.current.inventory,
							color: that.current.color,
							poster: that.current.poster,
							pic1: that.current.pic1,
							pic2: that.current.pic2,
							cdate: that.current.cdate,
							token: localStorage.getItem("yw_token"),
						},
						complete (msg){
							console.log(msg);
						}
					}).then(response => {
						console.log(response);
						if(response.type == 'success'){
							that.$refs.edit.close();
						}else if(response.type == "error"){
							//服务器返回其他原因
						}
						that.$store.commit('TOGGLE_WARNING',{
							msg: response.msg,
							visibility: true,
						});
					}).catch(status => {
						console.log(status);
					});
				}
			},
			add () {
				this.current = {};//清空edit页面数据
				this.type = 'add';
				this.$refs.edit.open('add');
			},
			edit (e,index) {
				this.focus = index;
				this.current = this.shops[this.focus];
				this.type = 'edit';
				this.$refs.edit.open('edit');
			},
			remove () {
				if(this.shops.length != 0){
					this.$refs.dialog.open('确认要删除选中的商品?');
				}
			},
			dOk () {
				let that = this;
				let arr_id = [];
				that.shops.forEach((item) => {
					if(item.checked)
						arr_id.push(item.id);
				});
				if(arr_id.length != 0){
					ajax({
						url: "http://rainbowvs.com/yuewang/ywwms/interface/shop.php",
						overtime: 3000,
						data: {
							handle: 'del',
							ids: arr_id.join(","),
							token: localStorage.getItem("yw_token"),
						},
						complete (msg){
							console.log(msg);
						}
					}).then(response => {
						arr_id = [];
						console.log(response);
						if(response.type == 'success'){
							let temp = [];
							that.shops.forEach((item) => {
								if(!item.checked)
									temp.push(item);
							});
							that.shops = temp;
							that.$refs.dialog.close();
						}else if(response.type == "error"){
							//服务器返回其他原因
						}
						that.$store.commit('TOGGLE_WARNING',{
							msg: response.msg,
							visibility: true,
						});
					}).catch(status => {
						console.log(status);
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
				&>.pagination{
					margin: 5px 0;
					height: 33px;
					&>ul{
						line-height: 31px;
						position: fixed;
						overflow: hidden;
						&>li{
							margin-left: -1px;
							float: left;
							border:1px solid #dfe6ec;
							padding: 0 10px;
							cursor: pointer;
							position: relative;
							&.prev{
								margin-left: 0;
							}
							&.dot{
								padding: 1px 10px;
								cursor: default;
								border: 0;
							}
							&:hover{
								z-index: 1;
								border-color: #324157;
							}
							&.active{
								border-color: #324157;
								background-color: #324157;
								color: #fff;
							}
						}
						&>p{
							margin: 0 10px;
							line-height: 33px;
							float: left;
							&>input{
								line-height: 26px;
								border: 1px solid #dfe6ec;
								text-align: center;
								width: 50px;
							}
						}
						&>button{
							margin: 3px 0;
							float: left;
							width: 50px;
						    height: 27px;
						    cursor: pointer;
						    background-color: #324157;
						    color: #bfcbd9;
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
								padding: 0 10px;
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