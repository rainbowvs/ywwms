<template>
	<div class="header">
		<h1><router-link to="/" @click.native="toggle"><i class="fa fa-window-restore"></i>后台管理系统</router-link></h1>
		<div class="user">
			<p>
				欢迎您，管理员：<span id="adminName" @click="downing($event)">{{adminName}}</span>
				<transition name="pull">
					<ul v-show="downed">
						<li @click="profile($event)"><i class="fa fa-folder-open"></i>个人</li>
						<li @click="logout($event)"><i class="fa fa-sign-out"></i>退出</li>
					</ul>
				</transition>
			</p>
		</div>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				downed: false,
				adminName: ''
			}
		},
		created () {
			let that = this;
			that.$ajax({
				url: "http://rainbowvs.com/yuewang/ywwms/interface/getAdminName.php",
				overtime: 3000,
				data: {
					token: localStorage.getItem("yw_token"),
				},
				complete (msg){
					console.log(msg);
				}
			}).then( response => {
				if(response.type == 'success')
					that.adminName = response.name;
				else if(response.type == "error"){
					that.$store.commit('TOGGLE_WARNING',{
						msg: response.msg,
						visibility: true,
					});
					localStorage.removeItem("yw_token");
					setTimeout(()=>{
						that.$store.commit('TOGGLE_WARNING',{
							msg: '',
							visibility: false,
						});
						that.$router.push('/login');
					},1000);
				}
					
			}).catch( status => {
				console.log(status);
			})
		},
		mounted () {
			document.onclick = (e) => {
				if(this.downed && (e.target.id != 'adminName'))
					this.downed = false;
			}
		},
		methods: {
			downing (e) {
				if(!this.downed)
					this.downed = true;
				else
					this.downed = false;
			},
			profile (e) {
				if(!this.downed)
					this.downed = true;
				else
					this.downed = false;
				this.$router.push('/administrator');
				this.$store.commit('UPDATE_CURRENTPAGE',3);
			},
			logout (e) {
				this.downed = false;
				localStorage.removeItem("yw_token");
				this.$store.commit('UPDATE_CURRENTPAGE',0);
				localStorage.removeItem("currentPage");
				this.$store.commit('TOGGLE_WARNING',{
					msg: '退出成功',
					visibility: true,
				});
				setTimeout(() => {
					this.$store.commit('TOGGLE_WARNING',{
						msg: '',
						visibility: false,
					});
					this.$router.push('/login');
				},1000);
			},
			toggle () {
				this.$store.commit('UPDATE_CURRENTPAGE',0);
			}
		},
	}
</script>

<style lang="scss" scoped>
	.header{
		position: fixed;
	    box-sizing: border-box;
	    width: 100%;
	    height: 66px;
	    line-height: 66px;
	    font-size: 14px;
	    background-color: #324157;
	    box-shadow: 2px 0 3px rgba(0,0,0,.5);
	    z-index: 1000;
	    padding: 0 40px 0 40px;
	    &>h1{
	    	float: left;
	    	color: #fff;
	    	font-size: 20px;
	    	letter-spacing: 2px;
	    	&>a{
	    		display: block;
	    		color: #fff;
	    		&>i{
	    			margin-right: 10px;
	    		}
	    	}
	    }
	    &>.user{
	    	float: right;
	    	&>p{
	    		position: relative;
	    		font-size: 16px;
	    		color: #bfcbd9;
	    		&>span{
	    			color: #fff;
	    			cursor: pointer;
					&:hover{
						color: #20A0FF;
					}
	    		}
	    		&>ul{
	    			position: absolute;
	    			right: 0px;
	    			top: 66px;
	    			border: 1px solid #324157;
	    			&>li{
	    				color: #bfcbd9;
	    				width: 80px;
	    				text-align: center;
	    				line-height: 28px;
	    				cursor: pointer;
	    				background-color: #324157;
	    				&:hover{
	    					background-color: #48576a;
	    					color: #20a0ff;
	    					&>i{
	    						color: #20a0ff;
	    					}
	    				}
	    				&>i{
	    					color: #bfcbd9;
	    					margin-right: 12px;
	    					font-size: 12px;
	    				}
	    			}
	    		}
	    	}
	    }
	}
	.pull-enter-active, .pull-leave-active {
		transition: opacity .4s
	}
	.pull-enter, .pull-leave-active {
		opacity: 0
	}

</style>