<template>
	<div class="index">
		<h2>首页</h2>
		<div class="container">
			<div class="fit">
				<table>
					<tbody>
						<tr>
							<th colspan="4">管理系统介绍</th>
						</tr>
						<tr>
							<th rowspan="5">模块</th>
							<th>提示</th>
							<td colspan="3">点击编辑按钮后鼠标悬停于输入框上会出现输入提示</td>
						</tr>
						<tr>
							<th>订单</th>
							<td colspan="2">普通管理员有管理权限，可对订单【改、删】操作</td>
						</tr>
						<tr>
							<th>商品</th>
							<td colspan="2">普通管理员有管理权限，可对商品【增、改、删】操作</td>
						</tr>
						<tr>
							<th>用户</th>
							<td colspan="2">普通管理员有管理权限，可对用户【增、改、删】操作</td>
						</tr>
						<tr>
							<th>管理员</th>
							<td>超级管理员才有管理权限，可对管理员【增、改、删】操作</td>
							<td>如果对正在登录的管理员账号进行权限操作必须重新登录才能进行后续操作</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="fit">
				<table>
					<tbody>
						<tr>
							<th colspan="2">登录管理员信息</th>
						</tr>
						<tr>
							<th>编号</th>
							<td v-text="$store.state.adminInfo.id"></td>
						</tr>
						<tr>
							<th>管理员</th>
							<td v-text="$store.state.adminInfo.name"></td>
						</tr>
						<tr>
							<th>等级</th>
							<td v-text="$store.state.adminInfo.level"></td>
						</tr>
						<tr>
							<th>账号</th>
							<td v-text="$store.state.adminInfo.phone"></td>
						</tr>
						<tr>
							<th>最后登录时间</th>
							<td v-text="$store.state.adminInfo.l_date"></td>
						</tr>
						<tr>
							<th>注册时间</th>
							<td v-text="$store.state.adminInfo.r_date"></td>
						</tr>
						<tr>
							<th>操作</th>
							<td>
								<button @click="edit('updateName')">编辑名字</button>
								<button @click="edit('updatePwd')">编辑密码</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<my-edit ref="edit" @sure="eOk">
			<h1 slot="header">修改个人资料</h1>
			<form slot="body">
				<ul>
					<li v-if="type=='updateName'">
						<label for="name">昵称</label>
						<input type="text" id="name" v-model="current.name" title="20位字符以内" placeholder="20位字符以内" />
					</li>
					<li v-if="type=='updatePwd'">
						<label for="pwd">旧密码</label>
						<input type="password" id="pwd" v-model="pwd" title="长度6~12，至少包含一位字母和一位数字" placeholder="长度6~12，至少包含一位字母和一位数字" />
					</li>
					<li v-if="type=='updatePwd'">
						<label for="newPwd">新密码</label>
						<input type="password" id="newPwd" v-model="newPwd" title="长度6~12，至少包含一位字母和一位数字" placeholder="长度6~12，至少包含一位字母和一位数字" />
					</li>
				</ul>
			</form>
		</my-edit>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				type: 'updateName',
				current: {},
				pwd: '',
				newPwd: '',
			}
		},
		created () {
			let that = this;
		},
		methods: {
			eOk () {
				let that = this;
				if(that.type == 'updateName'){
					if(!(that.checkName(that.current.name)))
						return false;
				}else if(that.type == 'updatePwd'){
					if(!(that.checkPwd(that.pwd) && that.checkNewPwd(that.newPwd)))
						return false;
				}
				//提交修改订单信息
				that.$ajax({
					name: '修改个人资料',
					url: window.reqUrl + 'ywms_admin.php',
					data: {
						handle: that.type,
						uid: that.$store.state.adminInfo['id'],
						token: that.$store.state.adminInfo['token'],
						id: that.current.id,
						phone: that.current.phone,
						name: that.current.name,
						pwd: that.pwd,
						newPwd: that.newPwd,
					},
					beforeSend () {
						that.$store.commit('TOGGLE_LOADING',true);
					}
				}).then(res => {
					if(res.type == 'success'){
						that.$store.commit('SET_ADMININFO',{
							adminInfo: res.adminInfo,
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
			},
			edit (type) {
				this.type = type;
				this.current = JSON.parse(JSON.stringify(this.$store.state.adminInfo));
				this.$refs.edit.open();
			},
			checkPwd (pwd) {
				if(pwd == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写旧密码',
						visibility: true,
					});
					return false;
				}else if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(pwd)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '旧密码不得包含敏感字符',
						visibility: true,
					});
					return false;
				}else if(!(/(?=.*\d)(?=.*[a-z])/g.test(pwd))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '旧密码至少包含一个字母和一个数字',
						visibility: true,
					});
					return false;
				}else if(pwd.length < 6 || pwd.length > 12){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '旧密码长度范围在6-12个字符',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			checkNewPwd (pwd) {
				if(pwd == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '请填写新密码',
						visibility: true,
					});
					return false;
				}else if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(pwd)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '新密码不得包含敏感字符',
						visibility: true,
					});
					return false;
				}else if(!(/(?=.*\d)(?=.*[a-z])/g.test(pwd))){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '新密码至少包含一个字母和一个数字',
						visibility: true,
					});
					return false;
				}else if(pwd.length < 6 || pwd.length > 12){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '新密码长度范围在6-12个字符',
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
		},
		components: {
			'my-edit': () => import('@/components/common/Edit'),//webpack2官网推荐使用, 属于es7范畴, 需要配合babel的syntax-dynamic-import插件使用,
		},
	}
</script>

<style lang="scss" scoped>
	.index{
		width: 100%;
		height: 90%;
		&>h2{
			border-left: 4px solid #324157;
			padding-left: 10px;
			margin-bottom: 20px;
		}
		&>.container{
			width: 100%;
			height: 100%;
			&>.fit{
				width: 100%;
				border: 1px solid #d1dbe5;
			    border-radius: 4px;
			    background-color: #fff;
			    box-shadow: 0 2px 4px 0 rgba(0,0,0,.12), 0 0 6px 0 rgba(0,0,0,.04);
			    box-sizing: border-box;
			    padding: 20px;
			    margin-bottom: 50px;
				&>table{
					width: 100%;
					text-align: center;
					vertical-align: middle;
					border-collapse: collapse;
					&>tbody{
						width: 100%;
						&>tr{
							&:hover{
								background-color: #eef1f6;;
							}
							&>th{
								padding: 0 10px;
								height: 40px;
								background-color: #eef1f6;
								color: #000;
								border: 1px solid #dfe6ec;
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
									padding: 5px 10px;
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
	    color: #ccc;
	}
</style>