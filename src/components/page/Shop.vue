<template>
	<div class="shop">
		<h2>商品信息</h2>
		<div class="container">
			<div class="fit">
				<table>
					<tbody>
						<tr>
							<th>选择</th>
							<th>编号</th>
							<th>名称</th>
							<th>种类</th>
							<th>单价</th>
							<th>材质</th>
							<th>重量</th>
							<th>圈口</th>
							<th>链长</th>
							<th>库存</th>
							<th>创建时间</th>
							<th>操作</th>
						</tr>
						<template v-if="shops.length==0">
							<tr>
								<td colspan="12">暂时没有找到商品信息</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="shop,index in shops">
								<td><input type="checkbox" v-model="shop.checked" /></td>
								<td>{{shop.id}}</td>
								<td>{{shop.name}}</td>
								<td>
									<template v-if="shop.style==0">戒指</template>
									<template v-else-if="shop.style==1">项链</template>
									<template v-else-if="shop.style==2">吊坠</template>
									<template v-else-if="shop.style==3">耳环</template>
								</td>
								<td>￥{{shop.price}}</td>
								<td>
									<template v-if="shop.type==0">黄金</template>
									<template v-else-if="shop.type==1">钻石</template>
									<template v-else-if="shop.type==2">K金</template>
									<template v-else-if="shop.type==3">铂金</template>
									<template v-else-if="shop.type==4">珍珠</template>
									<template v-else-if="shop.type==5">银饰</template>
									<template v-else-if="shop.type==6">宝石</template>
								</td>
								<td>约{{shop.mass}}g</td>
								<td>{{shop.size}}</td>
								<td>{{shop.length}}</td>
								<td>{{shop.inventory}} 件</td>
								<td>{{shop.cdate}}</td>
								<td><button @click="edit($event,index)">编辑</button></td>
							</tr>
						</template>
						<tr>
							<td><input type="checkbox" v-model="checkedAll" /></td>
							<td><button @click="remove">删除</button></td>
							<td><button @click="add">增加</button></td>
							<td colspan="9"></td>
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
				<h1 slot="header">修改商品</h1>
			</template>
			<template v-else>
				<h1 slot="header">增加商品</h1>
			</template>
			<form slot="body">
				<ul>
					<li>
						<label for="name">名称</label>
						<input type="text" id="name" v-model="current.name" title="60个字符以内" placeholder="60个字符以内" maxlength="60" />
					</li>
					<li>
						<label for="style">种类</label>
						<input type="text" id="style" v-model="current.style" title="输入对应数字，戒指=>0,项链=>1,吊坠=>2,耳环=>3" placeholder="输入对应数字,戒指=>0,项链=>1,吊坠=>2,耳环=>3" maxlength="1" />
					</li>
					<li>
						<label for="price">单价</label>
						<input type="text" id="price" v-model="current.price" title="单位无需填，默认人民币/元，精确到两位小数" placeholder="精确到两位小数" maxlength="13" />
					</li>
					<li>
						<label for="type">材质</label>
						<input type="text" id="type" v-model="current.type" title="输入对应数字，黄金=>0,钻石=>1,西金=>2,铂金=>3,珍珠=>4,银饰=>5,宝石=>6" placeholder="黄金=>0,钻石=>1,西金=>2,铂金=>3,珍珠=>4,银饰=>5,宝石=>6" maxlength="1" />
					</li>
					<li>
						<label for="mass">质量</label>
						<input type="text" id="mass" v-model="current.mass" title="单位无需填，默认克/g，精确到两位小数" placeholder="精确到两位小数"  maxlength="6" />
					</li>
					<li>
						<label for="size">圈口</label>
						<input type="text" id="size" v-model="current.size" title="单位无需填，正整数，升序" placeholder="可不填，正整数，尺寸之间以英文逗号分割，升序" maxlength="28" />
					</li>
					<li>
						<label for="length">链长</label>
						<input type="text" id="length" v-model="current.length" title="单位无需填，正整数，升序" placeholder="可不填，正整数，尺寸之间以英文逗号分割，升序" maxlength="28" />
					</li>
					<li>
						<label for="inventory">库存</label>
						<input type="text" id="inventory"  v-model="current.inventory" title="单位无需填，正整数，可为0" placeholder="正整数,可为0" maxlength="6" />
					</li>
					<li>
						<label for="model">模号</label>
						<input type="text" id="model" v-model="current.model" title="数字英文混合10个字符以内" placeholder="数字英文混合10个字符以内" maxlength="10" />
					</li>
					<li>
						<label for="processingCost">加工费</label>
						<input type="text" id="processingCost" v-model="current.processingCost" title="单位无需填，精确到两位小数" placeholder="精确到两位小数" maxlength="6" />
					</li>
					<li>
						<label for="poster">封面</label>
						<input type="text" id="poster" v-model="current.poster" title="http://xxx.xxx" placeholder="输入完整图片地址" maxlength="150" />
					</li>
					<li>
						<label for="preview">详情图片</label>
						<input type="text" id="preview" v-model="current.preview" title="http://xxx.xxx,http://xxx.xxx,http://xxx.xxx"  placeholder="数量1~4张，以英文逗号分割" maxlength="450" />
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
				shops: [],
				focus: null,
				type: '',
				totalPage: 1,//总页数
				currentPage: 1,//当前显示第几页
				showPage: 5,//显示的页数[只能输入奇数,偶数最后一页无法显示]
			}
		},
		created () {
			this.getShopInfo(1);
		},
		components: {
			'my-edit': () => import('@/components/common/Edit'),//webpack2官网推荐使用, 属于es7范畴, 需要配合babel的syntax-dynamic-import插件使用,
			'my-pagination': () => import('@/components/common/Pagination'),
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
			checkName (name) {
				if(name == '' || name == undefined){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写名称',
						visibility: true,
					});
					return false;
				}else if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(name)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '名称不得包含敏感字符',
						visibility: true,
					});
					return false;
				}else if(name.length >= 60){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '名称长度不得超过60个字符',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkStyle (style) {
				if(style == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写种类',
						visibility: true,
					});
					return false;
				}else if(!(/[0-3]{1}/g.test(style))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '种类范围0~3的整数',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkPrice (price) {
				if(price == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写价格',
						visibility: true,
					});
					return false;
				}else if(!(/^(0|[1-9][0-9]{0,9})(\.){1}(\d){2}$/g.test(price))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '价格范围0~9999999999.99保留2位小数',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkType (type) {
				if(type == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写材质',
						visibility: true,
					});
					return false;
				}else if(!(/[0-6]{1}/g.test(type))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '材质范围0~6的整数',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkMass (mass) {
				if(mass == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写重量',
						visibility: true,
					});
					return false;
				}else if(!(/^(0|[1-9][0-9]{0,2})(\.){1}(\d){2}$/g.test(mass))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '重量范围0~999.99保留2位小数',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkSize (size) {
				size = '';
				if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(size)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '圈口可不填,但不能有敏感字符[";:?\/ ]',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkLength (length) {
				length = '';
				if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(length)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '链长可不填,但不能有敏感字符[";:?\/ ]',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkInventory (inventory) {
				if(inventory == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写库存',
						visibility: true,
					});
					return false;
				}else if(!(/^(0|[1-9][0-9]{0,5})$/g.test(inventory))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '库存范围0~999999的整数',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkModel (model) {
				if(model == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写模号',
						visibility: true,
					});
					return false;
				}else if(!(/^(\w){1,10}$/g.test(model))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '模号10位以内的字符',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkPc (pc) {
				if(pc == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写加工费',
						visibility: true,
					});
					return false;
				}else if(!(/^(0|[1-9][0-9]{0,2})(\.){1}(\d){2}$/g.test(pc))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '加工费范围0~999.99保留2位小数',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkPoster (poster) {
				if(poster == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写封面地址',
						visibility: true,
					});
					return false;
				}else if(!(/^http(s)?\:([^\"\']+)\.(jpg|png)/i.test(poster))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请输入正确的封面地址',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkPreview (preview) {
				if(preview == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写详情图片地址',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			getShopInfo (currentPage) {
				let that = this;
				that.$ajax({
					name: '获取所有商品列表',
					url: window.reqUrl + 'ywms_shop.php',
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
						that.shops = [];
						res.shops.forEach((v,i) => {
							that.shops.push({
								id: v.id,
								name: v.name,
								style: v.style,
								inventory: v.inventory,
								price: v.price,
								poster: v.poster,
								preview: v.preview,
								processingCost: v.processing_cost,
								type: v.type,
								mass: v.mass,
								size: v.size,
								length: v.length,
								model: v.model,
								cdate: v.c_date,
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
				this.getShopInfo(oldValue);
			},
			toPrev (oldValue) {
				this.currentPage = oldValue;
				this.getShopInfo(oldValue);
			},
			toNext (oldValue) {
				this.currentPage = oldValue;
				this.getShopInfo(oldValue);
			},
			selectPage (page) {
				this.getShopInfo(page);
			},
			eOk (index) {
				let that = this;
				if(!(
					that.checkName(that.current.name) && 
					that.checkStyle(that.current.style) && 
					that.checkPrice(that.current.price) &&
					that.checkType(that.current.type) &&
					that.checkMass(that.current.mass) &&
					that.checkSize(that.current.size) &&
					that.checkLength(that.current.length) &&
					that.checkInventory(that.current.inventory) &&
					that.checkModel(that.current.model) &&
					that.checkPc(that.current.processingCost) &&
					that.checkPoster(that.current.poster) &&
					that.checkPreview(that.current.preview)
				))
					return false;
				if(that.type == 'add'){
					//提交新增商品信息
					that.$ajax({
						name: '新增商品',
						url: window.reqUrl + 'ywms_shop.php',
						data: {
							handle: 'set',
							uid: that.$store.state.adminInfo['id'],
							token: that.$store.state.adminInfo['token'],
							name: that.current.name,
							style: that.current.style,
							price: that.current.price,
							type: that.current.type,
							mass: that.current.mass,
							size: that.current.size,
							length: that.current.length,
							inventory: that.current.inventory,
							model: that.current.model,
							poster: that.current.poster,
							preview: that.current.preview,
							processingCost: that.current.processingCost,
							cdate: that.current.cdate,
						},
						beforeSend () {
							that.$store.commit('TOGGLE_LOADING',true);
						}
					}).then(res => {
						if(res.type == 'success'){
							that.shops.push({
								id: res.shop.id,
								name: res.shop.name,
								style: res.shop.style,
								inventory: res.shop.inventory,
								price: res.shop.price,
								poster: res.shop.poster,
								preview: res.shop.preview,
								processingCost: res.shop.processing_cost,
								type: res.shop.type,
								mass: res.shop.mass,
								size: res.shop.size,
								length: res.shop.length,
								model: res.shop.model,
								cdate: res.shop.c_date,
								checked: false,
							});
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
				}else if(that.type == 'edit'){
					if(that.$compareJson(that.shops[index],that.current)){
						that.$store.commit('TOGGLE_WARNING',{
							msg: '没做任何修改',
							visibility: true,
						});
					}else{
						//提交修改商品信息
						that.$ajax({
							name: '修改商品',
							url: window.reqUrl + 'ywms_shop.php',
							data: {
								handle: 'update',
								uid: that.$store.state.adminInfo['id'],
								token: that.$store.state.adminInfo['token'],
								id: that.current.id,
								name: that.current.name,
								style: that.current.style,
								price: that.current.price,
								type: that.current.type,
								mass: that.current.mass,
								size: that.current.size,
								length: that.current.length,
								inventory: that.current.inventory,
								model: that.current.model,
								poster: that.current.poster,
								preview: that.current.preview,
								processingCost: that.current.processingCost,
								cdate: that.current.cdate,
							},
							beforeSend () {
								that.$store.commit('TOGGLE_LOADING',true);
							}
						}).then(res => {
							if(res.type == 'success'){
								that.shops.splice(index,1,that.current);
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
			add () {
				this.current = {};//清空edit页面数据
				this.type = 'add';
				this.$refs.edit.open('add');
			},
			edit (e,index) {
				this.focus = index;
				this.current = JSON.parse(JSON.stringify(this.shops[this.focus]));
				this.type = 'edit';
				this.$refs.edit.open(index);
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
					that.$ajax({
						name: '删除指定商品',
						url: window.reqUrl + 'ywms_shop.php',
						data: {
							handle: 'del',
							uid: that.$store.state.adminInfo['id'],
							token: that.$store.state.adminInfo['token'],
							ids: arr_id.join(","),
						},
						beforeSend () {
							that.$store.commit('TOGGLE_LOADING',true);
						}
					}).then(res => {
						arr_id = [];
						if(res.type == 'success'){
							let temp = [];
							that.shops.forEach((item) => {
								if(!item.checked)
									temp.push(item);
							});
							that.shops = temp;
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
						msg: '没有选中任何商品',
						visibility: true,
					});
					that.$refs.dialog.close();
				}
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
								padding: 0 20px;
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
						min-width: 550px;
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