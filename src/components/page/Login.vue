<template>
	<div class="login">
		<div class="mask"></div>
		<div class="container">
			<div class="logo"><i class="fa fa-cog fa-spin"></i></div>
			<form v-on:submit.prevent="login($event)">
				<input type="text" name="phone" id="phone" placeholder="请输入11位手机号码" title="请输入11位手机号码" v-model="phone" autofocus/>
				<input type="password" name="pwd" id="pwd" placeholder="请输入6~12位密码，至少包含1位字母和1位数字" title="请输入6~12位密码，至少包含1位字母和1位数字" v-model="pwd" />
				<input type="submit" value="登录" />
			</form>
		</div>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				phone: '',
				pwd: '',
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
				}else if(pwd.length < 6 || pwd.length > 16){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '密码长度范围在6-16个字符',
						visibility: true,
					});
					return false;
				}
				return true;
			},
			login (e) {
				//点击登录按钮事件
				let that = this;
				if(!(that.checkPhone(that.phone) && that.checkPwd(that.pwd)))
					return false;
				that.$ajax({
					name: '登录',
					url: window.reqUrl + 'ywms_login.php',
					data: {
						phone: that.phone,
						pwd: that.pwd,
					},
					beforeSend () {
						that.$store.commit('TOGGLE_LOADING',true);
					}
				}).then(res => {
					if(res.type == 'success'){
						that.$store.commit('TOGGLE_WARNING',{
							msg: res.msg,
							visibility: true,
						});
						//存储用户信息
						that.$store.commit('SET_ADMININFO',{
							adminInfo: res.adminInfo,
						});
						setTimeout(() => {
							that.$router.push({path: '/'});
						},1000);
					}else{
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
		},
	}
</script>

<style lang="scss" scoped>
	.login{
		position: relative;
		width: 100%;
		height: 100%;
		background: url(../../../static/img/bg.jpg) no-repeat center center;
		display: table;
		text-align: center;
		vertical-align: middle;
		&>.container{
			display: table-cell;
			vertical-align: middle;
			text-align: center;
			background-color: rgba(0,0,0,0.2);
			&>.logo{
				box-sizing: border-box;
				padding-top: 10px;
				width: 84px;
				height: 84px;
				line-height: 64px;
				border-radius: 50%;
				background-color: rgba(255,255,255,0.6);
				margin: 0 auto;
				vertical-align: middle;
				&>i{
					display: block;
					vertical-align: middle;
					font-size: 64px;
				}
			}
			&>form{
				margin: 0 auto;
				width: 500px;
				&>#phone,&>#pwd{
					background: rgba(255,255,255,0.2);
					margin: 30px auto;
					display: block;
					width: 100%;
					height: 50px;
					line-height: 50px;
					padding: 0 20px;
					box-sizing: border-box;
					border-bottom: 1px solid #bfcbd9;
					font-size: 20px;
					color: #fff;
					font-family: "microsoft yahei";
				}
				&>input[type=submit]{
					box-sizing: border-box;
					display: block;
					width: 100%;
					height: 50px;
					line-height: 50px;
					background: rgba(255,255,255,0.6);
					cursor: pointer;
					font-size: 20px;
					letter-spacing: 2px;
					border: 1px solid rgba(255,255,255,0.6);
					transition: all 0.4s; 
					&:hover{
						color: #fff;
						border-color: rgba(255,255,255,0.6);
						background: transparent;
					}
				}
			}
			&>button{
				margin: 30px auto;
				width: 500px;
				height: 50px;
				line-height: 50px;
				transition: all 0.4s;
				border: 1px solid #bfcbd9;
				box-sizing: border-box;
				cursor: pointer; 
				&:hover{
					color: #fff;
					border-color: rgba(255,255,255,0.6);
					background: transparent;
				}
			}
		}
	}
	input::-webkit-input-placeholder{
		color: #aaa;
		font-size: 16px;
	}
</style>