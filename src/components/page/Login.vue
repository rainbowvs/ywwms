<template>
	<div class="login">
		<div class="mask"></div>
		<div class="container">
			<div class="logo"><i class="fa fa-cog fa-spin"></i></div>
			<form v-on:submit.prevent="login($event)">
				<input type="text" name="user" id="user" placeholder="User" v-model="user" autofocus/>
				<label></label>
				<input type="password" name="pwd" id="pwd" placeholder="Pwd" v-model="pwd" />
				<label></label>
				<!--<div>
					<input type="text" name="code" id="code" v-model="code" placeholder="Code" />
					<label></label>
					<img :src="codeUrl" @click="toggle"/>
				</div>-->
				<input type="submit" value="login" />
			</form>
		</div>
	</div>
</template>

<script>
	import store from '@/vuex/store';
	import {ajax} from '../../../static/js/ajax.js';
	export default{
		data () {
			return {
				user: '',
				pwd: '',
				code: '',
			}
		},
		store,
		mounted () {
			
		},
		methods: {
			validate () {
				if(/and|or|\/|\'|\"|\;|\:|\?|\\|\s/g.test(this.user)){
					return 'false';
				}
			},
			login (e) {
				let that = this;
				if(that.user == '' || that.pwd == ''){
					that.$store.commit('TOGGLE_WARNING',{
						msg: '账号或密码不能为空',
						state: true,
					});
				}else if(that.user.length < 6){
					that.$store.commit('TOGGLE_WARNING',{
						msg: '账号不得小于6位',
						state: true,
					});
				}else if(that.pwd < 6){
					that.$store.commit('TOGGLE_WARNING',{
						msg: '密码不得小于6位',
						state: true,
					});
				}else if(that.validate() == 'false'){
					that.$store.commit('TOGGLE_WARNING',{
						msg: '账号不能包含敏感字符',
						state: true,
					});
				}else{
					ajax({
						url: "http://rainbowvs.com/yuewang/ywwms/interface/login.php",
						type: "post",
						async: true,
						overtime: 3000,
						data: {
							'user': that.user,
							'pwd': that.pwd,
						},
						beforeSend () {
							that.$store.commit('TOGGLE_LOADING',true);
						},
						complete (msg) {
							that.$store.commit('TOGGLE_LOADING',false);
							console.log(msg);
						}
					}).then(response => {
						console.log(response);
						that.$store.commit('TOGGLE_LOADING',false);
						that.$store.commit('TOGGLE_WARNING',{
							msg: response.msg,
							state: true,
						});
						if(response.type == 'success'){
							localStorage.setItem('yw_token',response.token);
							setTimeout(() => {
								that.$store.commit('TOGGLE_WARNING',{
									msg: '',
									state: false,
								});
								that.$router.push({ path: '/', query: { token: response.token}});
							},1000);
						}
					}).catch(status => {
						console.log(status);
					});
				}
			},
		},
	}
</script>

<style lang="scss" scoped>
	.login{
		position: relative;
		width: 100%;
		height: 100%;
		text-align: center;
		background: url(../../../static/img/bg.jpg) no-repeat center center;
		&>.mask{
			position: absolute;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: url(../../../static/img/bg.jpg) no-repeat center center;
			filter: blur(20px);
		}
		&>.container{
			display: flex;
			flex-direction: column;
			justify-content: center;
			width: 100%;
			height: 100%;
			position: absolute;
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
				&>#user,&>#pwd{
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
				&>div{
					overflow: hidden;
					margin-bottom: 30px;
					&>#code{
						width: 300px;
						float: left;
						background: rgba(255,255,255,0.2);
						display: block;
						height: 50px;
						line-height: 50px;
						padding: 0 20px;
						box-sizing: border-box;
						border-bottom: 1px solid #bfcbd9;
						font-size: 20px;
						color: #fff;
						font-family: "microsoft yahei";
					}
					&>img{
						cursor: pointer;
						float: left;
					}
				}
			}
		}
	}
	input::-webkit-input-placeholder{
		color: #aaa;
	}
</style>