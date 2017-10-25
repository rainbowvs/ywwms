<template>
	<div class="administrator">
		<h2>管理员信息</h2>
		<div class="container">
			<ul>
				<li>
					<label for="user">账号：</label>
					<input type="text" id="user" v-model="administrator.user" />
				</li>
				<li>
					<label for="name">姓名：</label>
					<input type="text" id="name" v-model="administrator.name" />
				</li>
				<li>
					<label for="priority">权限：</label>
					<input type="text" id="priority" v-model="administrator.priority" />
				</li>
				<li>
					<label for="regdate">注册时间：</label>
					<input type="text" id="regdate" v-model="administrator.rdate" />
				</li>
				<li><button @click="save">保存</button></li>
			</ul>
		</div>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				administrator: {user: 'rainbowvs',name: '彭子俊',priority: '管理员',rdate: "2017-10-17 9:40:13"},
			}
		},
		methods: {
			save () {
				
			},
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
			box-shadow: 0 2px 4px 0 rgba(0,0,0,.12), 0 0 6px 0 rgba(0,0,0,.04);
			width: 600px;
			border: 1px solid #d1dbe5;
			border-radius: 4px;
			background-color: #fff;
			padding: 20px;
			&>ul{
				border: 1px solid #d1dbe5;
				&>li{
					border-top: 1px solid #d1dbe5;
					box-sizing: border-box;
					line-height: 80px;
					position: relative;
					&:first-child{
						border-top: 0;
					}
					&:last-child{
						padding-left: 65px;
					}
					&>label{
						position: absolute;
						cursor: text;
						left: 50px;
						top: 0;
					}
					&>input[type=text]{
						box-sizing: border-box;
						padding: 0 10px;
						width: 100%;
						font-family: "microsoft yahei";
						font-size: 18px;
						line-height: 80px;
						text-align: center;
						&:focus{
							background-color: #eef1f6;
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
							color: #fff;
						}
						&:active{
							color: #20a0ff;
						}
					}
				}
			}
		}
	}
</style>