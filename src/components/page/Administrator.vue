<template>
	<div class="administrator">
		<h2>管理员信息</h2>
		<div class="container">
			<div class="fit">
				<table>
					<tbody>
						<tr>
							<th>选择</th>
							<th>名字</th>
							<th>手机</th>
							<th>密码</th>
							<th>等级</th>
							<th>注册时间</th>
							<th>操作</th>
						</tr>
						<template v-if="admins.length==0">
							<tr>
								<td colspan="7">暂时没有找到管理员信息</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="admin,index in admins">
								<td><input type="checkbox" v-model="admin.checked" /></td>
								<td>{{admin.name}}</td>
								<td>{{admin.phone}}</td>
								<td>{{admin.pwd}}</td>
								<td>{{admin.level}}</td>
								<td>{{admin.rdate}}</td>
								<td><button @click="edit($event,index)">编辑</button></td>
							</tr>
						</template>
						<tr>
							<td><input type="checkbox" v-model="checkedAll" /></td>
							<td><button @click="remove">删除</button></td>
							<td><button @click="add">增加</button></td>
							<td colspan="5"></td>
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
				<h1 slot="header">修改管理员</h1>
			</template>
			<template v-else>
				<h1 slot="header">增加管理员</h1>
			</template>
			<form slot="body">
				<ul>
					<li>
						<label for="phone">手机</label>
						<input type="text" id="phone" v-model="current.phone" title="输入11位手机号码" placeholder="输入11位手机号码" maxlength="11" />
					</li>
					<li>
						<label for="name">名称</label>
						<input type="text" id="name" v-model="current.name" title="20个字符以内" placeholder="20个字符以内" maxlength="20" />
					</li>
					<li>
						<label for="pwd">密码</label>
						<input type="text" id="pwd" v-model="current.pwd" title="6~12位，至少包含1位数字和1位字母" placeholder="6~12位，至少包含1位数字和1位字母" maxlength="13" />
					</li>
					<li>
						<label for="level">等级</label>
						<input type="text" id="level" v-model="current.level" title="1位数字，范围1-7" placeholder="1位数字，范围1-7" maxlength="1" />
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
				admins: [],
				focus: null,
				type: '',
				totalPage: 1,//总页数
				currentPage: 1,//当前显示第几页
				showPage: 5,//显示的页数[只能输入奇数,偶数最后一页无法显示]
			}
		},
		created () {
			this.getAdminInfo(1);
		},
		components: {
			'my-edit': () => import('@/components/common/Edit'),
			'my-pagination': () => import('@/components/common/Pagination'),
		},
		computed: {
			checkedAll: {
				get () {
					if(this.checkedCount == this.admins.length){
						if(this.checkedCount == 0)
							return false;
						else
							return true;
					}
					return false;
				},
				set (value) {
					this.admins.forEach((item) => {
						item.checked = value;
					});
					return value;
				}
			},
			checkedCount: {
				get () {
					let count = 0;
					this.admins.forEach((item) => {
						if(item.checked)
							count++;
					});
					return count;
				}
			}
		},
		methods: {
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
						msg: '请填写昵称',
						visibility: true,
					});
					return false;
				}else if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(name)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '昵称不得包含敏感字符',
						visibility: true,
					});
					return false;
				}else if(name.length >= 20){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '昵称长度不得超过20个字符',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkPwd (pwd) {
				if(pwd == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写密码',
						visibility: true,
					});
					return false;
				}else if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(pwd)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '密码不得包含敏感字符',
						visibility: true,
					});
					return false;
				}else if(!(/(?=.*\d)(?=.*[a-z])/g.test(pwd))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '密码至少包含一个字母和一个数字',
						visibility: true,
					});
					return false;
				}else if(pwd.length < 6 || pwd.length > 12){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '密码长度范围在6-12个字符',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkLevel (level) {
				if(level == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写等级',
						visibility: true,
					});
					return false;
				}else if(!(/[1-7]{1}/g.test(level))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '等级范围1-7',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			getAdminInfo (currentPage) {
				let that = this;
				that.$ajax({
					name: '获取所有管理员列表',
					url: window.reqUrl + 'ywms_admin.php',
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
						that.admins = [];
						res.admins.forEach((v,i) => {
							that.admins.push({
								id: v.id,
								phone: v.phone,
								name: v.name,
								pwd: v.o_pwd,
								level: v.level,
								ldate: v.l_date,
								rdate: v.r_date,
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
				this.getAdminInfo(oldValue);
			},
			toPrev (oldValue) {
				this.currentPage = oldValue;
				this.getAdminInfo(oldValue);
			},
			toNext (oldValue) {
				this.currentPage = oldValue;
				this.getAdminInfo(oldValue);
			},
			selectPage (page) {
				this.getAdminInfo(page);
			},
			eOk (index) {
				let that = this;
				if(!(
					that.checkPhone(that.current.phone) && 
					that.checkName(that.current.name) && 
					that.checkPwd(that.current.pwd) &&
					that.checkLevel(that.current.level)
				))
					return false;
				if(that.type == 'add'){
					//提交新增管理员信息
					that.$ajax({
						name: '新增管理员',
						url: window.reqUrl + 'ywms_admin.php',
						data: {
							handle: 'set',
							uid: that.$store.state.adminInfo['id'],
							token: that.$store.state.adminInfo['token'],
							phone: that.current.phone,
							name: that.current.name,
							pwd: that.current.pwd,
							level: that.current.level,
						},
						beforeSend () {
							that.$store.commit('TOGGLE_LOADING',true);
						}
					}).then(res => {
						if(res.type == 'success'){
							that.admins.push({
								id: res.admin.id,
								phone: res.admin.phone,
								name: res.admin.name,
								pwd: res.admin.o_pwd,
								level: res.admin.level,
								ldate: res.admin.l_date,
								rdate: res.admin.r_date,
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
					if(that.$compareJson(that.admins[index],that.current)){
						that.$store.commit('TOGGLE_WARNING',{
							msg: '没做任何修改',
							visibility: true,
						});
					}else{
						//提交修改管理员信息
						that.$ajax({
							name: '修改管理员',
							url: window.reqUrl + 'ywms_admin.php',
							data: {
								handle: 'update',
								uid: that.$store.state.adminInfo['id'],
								token: that.$store.state.adminInfo['token'],
								id: that.current.id,
								phone: that.current.phone,
								name: that.current.name,
								pwd: that.current.pwd,
								level: that.current.level,
								cdate: that.current.cdate,
							},
							beforeSend () {
								that.$store.commit('TOGGLE_LOADING',true);
							}
						}).then(res => {
							if(res.type == 'success'){
								let temp = {
									id: res.admin.id,
									phone: res.admin.phone,
									name: res.admin.name,
									pwd: res.admin.o_pwd,
									level: res.admin.level,
									ldate: res.admin.l_date,
									rdate: res.admin.r_date,
									checked: that.current.checked,
								};
								that.$refs.edit.close();
								that.admins.splice(index,1,temp);
								if(res.status == 2){
									setTimeout(() => {
										that.$store.commit('DEL_ADMININFO');
										that.$router.push({name: 'Login'});
									},1000);
									that.$store.commit('TOGGLE_WARNING',{
										msg: '正在登录的管理员等级被修改，请重新登录',
										visibility: true,
									});
								}
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
				this.current = JSON.parse(JSON.stringify(this.admins[this.focus]));
				this.type = 'edit';
				this.$refs.edit.open(index);
			},
			remove () {
				if(this.admins.length != 0){
					this.$refs.dialog.open('确认要删除选中的管理员?');
				}
			},
			dOk () {
				let that = this;
				let arr_id = [];
				that.admins.forEach((item) => {
					if(item.checked)
						arr_id.push(item.id);
				});
				if(arr_id.length != 0){
					that.$ajax({
						name: '删除指定管理员',
						url: window.reqUrl + 'ywms_admin.php',
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
							that.admins.forEach((item) => {
								if(!item.checked)
									temp.push(item);
							});
							that.admins = temp;
							that.$refs.dialog.close();
							if(res.status == 3){
								setTimeout(() => {
									that.$store.commit('DEL_ADMININFO');
									that.$router.push({name: 'Register'});
								},1000);
								that.$store.commit('TOGGLE_WARNING',{
									msg: '正在登录的管理员账号被删除，请重新注册',
									visibility: true,
								});
							}
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
						msg: '没有选中任何管理员',
						visibility: true,
					});
					that.$refs.dialog.close();
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.administrator{
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