<template>
	<div class="user">
		<h2>用户信息</h2>
		<div class="container">
			<div class="fit">
				<table>
					<tbody>
						<tr>
							<th>选择</th>
							<th>名称</th>
							<th>手机</th>
							<th>级别</th>
							<th>注册时间</th>
							<th>最后登录时间</th>
							<th>操作</th>
						</tr>
						<template v-if="users.length==0">
							<tr>
								<td colspan="7">暂时没有找到用户信息</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="user,index in users">
								<td><input type="checkbox" v-model="user.checked" /></td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="user.name" />
									</template>
									<template v-else>
										{{user.name}}
									</template>
								</td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="user.phone" />
									</template>
									<template v-else>
										{{user.phone}}
									</template>
								</td>
								<td>
									<template v-if="editable && flag==index">
										<input type="text" v-model="user.vip" />
									</template>
									<template v-else>
										{{user.vip}}
									</template>
								</td>
								<td>{{user.rdate}}</td>
								<td>{{user.ldate}}</td>
								<td><button @click="edit($event,index)">编辑</button></td>
							</tr>
						</template>
						<tr>
							<td><input type="checkbox" v-model="checkedAll" /></td>
							<td><button @click="remove">删除</button></td>
							<td colspan="5"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				users: [
					{name: 'rainbowvs',phone: 15099976289,rdate: "2017-10-17 9:40:13",ldate: "2017-10-17 9:52:13",vip: 1,checked: false},
					{name: 'play9999',phone: 13011232133,rdate: "2017-10-10 9:40:13",ldate: "2017-10-20 9:52:13",vip: 6,checked: false},
				],
				flag: null,
				editable: false,
			}
		},
		beforeRouteEnter (to,from,next) {
			next( vm => {
				console.log(`enter`);
			});
		},
		computed: {
			checkedAll: {
				get () {
					if(this.checkedCount == this.users.length){
						if(this.checkedCount == 0)
							return false;
						else
							return true;
					}
					return false;
				},
				set (value) {
					this.users.forEach((item) => {
						item.checked = value;
					});
					return value;
				}
			},
			checkedCount: {
				get () {
					let count = 0;
					this.users.forEach((item) => {
						if(item.checked)
							count++;
					});
					return count;
				}
			}
		},
		methods: {
			edit (e,index) {
				this.editable = !this.editable;
				if(this.editable)
					e.target.innerText = '保存';
				else
					e.target.innerText = '编辑';
				this.flag = index;
			},
			remove () {
				let temp = [];
				this.users.forEach((item) => {
					if(!item.checked)
						temp.push(item);
				});
				this.users = temp;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.user{
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
			}
		}
	}
</style>