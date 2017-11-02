<template>
	<div class="index">
		<h2>首页</h2>
		<div class="container">
			<div class="fit">
				<div class="taskList">
					<h2>任务列表</h2>
					<hr />
					<input type="text" name="task" id="task" v-model="task" placeholder="回车增加任务" @keyup.enter="addTodo($event)" />
					<ul>
						<template v-if="todos.length==0">
							<h6>暂时没有设定任务</h6>
						</template>
						<template v-else>
							<li v-for="todo,index in todos">
								{{todo.content}}
								<i class="fa fa-minus-circle" @click="removeCtd($event,index)"></i>
							</li>
						</template>
					</ul>
				</div>
			</div>
			<div class="fit" :style="{width: '493px',height: '493px'}">
				<div class="myCharts" :style="{width: '453px',height: '453px'}"></div>
			</div> 
			<div class="fit">
				<div class="message">
					<h2>信息列表</h2>
					<hr />
					<table>
						<tbody>
							<tr>
								<th>选择</th>
								<th>发送人</th>
								<th>标题</th>
								<th>内容</th>
								<th>时间</th>
							</tr>
							<template v-if="msgs.length==0">
								<tr>
									<td colspan="5">暂时没有找到信息</td>
								</tr>
							</template>
							<template v-else>
								<tr v-for="msg,index in msgs">
									<td><input type="checkbox" v-model="msg.checked" /></td>
									<td>{{msg.sender}}</td>
									<td>{{msg.title}}</td>
									<td>{{msg.content}}</td>
									<td>{{msg.cdate}}</td>
								</tr>
							</template>
							<tr>
								<td><input type="checkbox" v-model="checkedAll" /></td>
								<td><button @click="removeCm">删除</button></td>
								<td colspan="3"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<my-dialog @click.native="setConfirm($event)"><span class="wtf">1</span></my-dialog>
	</div>
</template>

<script>
	import {ajax} from '../../../static/js/ajax.js';
	import Dialog from '@/components/common/Dialog';
	import echarts from 'echarts';
	import store from '@/vuex/store';
	export default{
		data () {
			return {
				chart: null,
				focus: null,
				eventType: null,
				returnValue: null,
				msgs: [],
				todos: [],
				task: '',
			}
		},
		store,
		created () {
			let that = this;
			//拉取任务列表
			ajax({
				url: "http://rainbowvs.com/yuewang/ywwms/interface/adminTask.php",
				overtime: 3000,
				data: {
					handle: 'get',
					token: localStorage.getItem("yw_token"),
				},
				complete (msg){
					console.log(msg);
				}
			}).then(response => {
				if(response.type == 'success'){
					for(let i in response.tasks)
						that.todos.push({id: response.tasks[i].id,content: response.tasks[i].content,cdate: response.tasks[i].cdate,focus: false});
				}else if(response.type == "error"){
					that.$store.commit('TOGGLE_WARNING',{
						msg: response.msg,
						state: true,
					});
				}
				console.log(that.todos);
			}).catch(status => {
				console.log(status);
			});
			//拉取信息列表
			ajax({
				url: "http://rainbowvs.com/yuewang/ywwms/interface/adminMsg.php",
				overtime: 3000,
				data: {
					handle: 'get',
					receiver: localStorage.getItem("yw_token"),
				},
				complete (msg){
					console.log(msg);
				}
			}).then(response => {
				if(response.type == 'success'){
					for(let i in response.msgs)
						that.msgs.push({id: response.msgs[i].id,title: response.msgs[i].title,content: response.msgs[i].content,receiver: response.msgs[i].receiver,sender: response.msgs[i].sender,cdate: response.msgs[i].cdate,checked: false});
				}else if(response.type == "error"){
					that.$store.commit('TOGGLE_WARNING',{
						msg: response.msg,
						state: true,
					});
				}
			}).catch(status => {
				console.log(status);
			});
		},
		methods: {
			addTodo (e) {
				if(this.task == ''){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '任务不能为空',
						state: true,
					});
				}else if(this.todos.length == 10){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '最多只能同时存在10个任务',
						state: true,
					});
				}else if(this.todos.find((item) => item.content == this.task)){
					this.$store.commit('TOGGLE_WARNING',{
						msg: '已有相同的任务',
						state: true,
					});
				}else{
					let that = this;
					ajax({
						url: "http://rainbowvs.com/yuewang/ywwms/interface/adminTask.php",
						overtime: 3000,
						data: {
							handle: 'add',
							content: that.task,
							token: localStorage.getItem("yw_token"),
						},
						complete (msg){
							console.log(msg);
						}
					}).then( response => {
						if(response.type == 'success'){
							console.log(response);
							that.todos.push({id: response.task.id,content: response.task.content,cdate: response.task.cdate,focus: false});
							that.$store.commit('TOGGLE_WARNING',{
								msg: response.msg,
								state: true,
							});
							that.task = '';
						}else if(response.type == "error"){
							that.$store.commit('TOGGLE_WARNING',{
								msg: response.msg,
								state: true,
							});
						}
					}).catch( status => {
						console.log(status);
					})
				}
			},
			removeCtd (e,index) {
				this.eventType = 'removeTodo';
				this.$store.commit('TOGGLE_CONFIRMING',{
					msg: '确认要删除该任务?',
					state: true,
				});
				this.focus = index;
			},
			removeTodo (value) {
				if(value){
					let that = this;
					ajax({
						url: "http://rainbowvs.com/yuewang/ywwms/interface/adminTask.php",
						overtime: 3000,
						data: {
							handle: 'del',
							id: that.todos[that.focus].id,
							token: localStorage.getItem("yw_token"),
						},
						complete (msg){
							console.log(msg);
						}
					}).then(response => {
						if(response.type == 'success'){
							that.todos.splice(that.focus,1);
							that.$store.commit('TOGGLE_WARNING',{
								msg: response.msg,
								state: true,
							});
							that.focus = null;
						}else if(response.type == "error"){
							that.$store.commit('TOGGLE_WARNING',{
								msg: response.msg,
								state: true,
							});
						}
					}).catch(status => {
						console.log(status);
					});
				}else{
					this.focus = null;
				}
			},
			removeCm () {
				if(this.msgs.length != 0){
					this.eventType = 'removeMsg';
					this.$store.commit('TOGGLE_CONFIRMING',{
						msg: '确认要删除选中的信息?',
						state: true,
					});
				}
			},
			removeMsg (value) {
				if(value){
					let that = this;
					let arr_id = [];
					that.msgs.forEach((item) => {
						if(item.checked)
							arr_id.push(item.id);
					});
					if(arr_id.length != 0){
						ajax({
							url: "http://rainbowvs.com/yuewang/ywwms/interface/adminMsg.php",
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
							arr_id = '';
							console.log(response);
							if(response.type == 'success'){
								let temp = [];
								that.msgs.forEach((item) => {
									if(!item.checked)
										temp.push(item);
								});
								that.msgs = temp;
								that.$store.commit('TOGGLE_WARNING',{
									msg: response.msg,
									state: true,
								});
							}else if(response.type == "error"){
								that.$store.commit('TOGGLE_WARNING',{
									msg: response.msg,
									state: true,
								});
							}
						}).catch(status => {
							console.log(status);
						});
					}else{
						that.$store.commit('TOGGLE_WARNING',{
							msg: '没有选中任何信息',
							state: true,
						});
					}
				}
			},
			setConfirm (e) {
				if(e.target.id == 'ok'){
					this.returnValue = true;
					if(this.eventType == 'removeTodo')
						this.removeTodo(this.returnValue);
					else if(this.eventType == 'removeMsg')
						this.removeMsg(this.returnValue);
				}else if(e.target.id == 'no'){
					this.returnValue = false;
					if(this.eventType == 'removeTodo')
						this.removeTodo(this.returnValue);
					else if(this.eventType == 'removeMsg')
						this.removeMsg(this.returnValue);
				}
				this.returnValue = null;
			}
		},
		mounted () {
			// 引入基本模板
			let echarts = require('echarts/lib/echarts');
			// 引入饼图组件
			require('echarts/lib/chart/pie');
			// 引入提示框和图例组件
			require('echarts/lib/component/tooltip');
			require('echarts/lib/component/legend');
			this.chart = echarts.init(document.getElementsByClassName("myCharts")[0]);
			this.chart.setOption({
			    title: {
			        text: '用户分布',
			        left: 'center',
			        textStyle: {
			        	fontSize: 30
			        },
		        	padding: [
			            0,  // 上
			            0, // 右
			            0,  // 下
			            0, // 左
			        ],
			    },
			    tooltip : {
			        trigger: 'item',
			        formatter: "{a} <br/>{b} : {c} ({d}%)"
			    },
			    series : [
			        {
			            name: '访问来源',
			            type: 'pie',
			            radius : '70%',
			            center: ['50%', '50%'],
			            data:[
			                {value:335, name:'北京'},
			                {value:310, name:'上海'},
			                {value:274, name:'广州'},
			                {value:235, name:'天津'},
			                {value:400, name:'深圳'}
			            ].sort(function (a, b) { return a.value - b.value; }),
			            label: {
			                normal: {
			                    textStyle: {
			                        fontSize: 20
			                    }
			                }
			            },
			            labelLine: {
			            	normal: {
			            		length: 5,
			            		length2: 10,
			            	}
			            },
			            roseType: 'radius',
			            animationType: 'scale',
			            animationEasing: 'elasticOut',
			            animationDelay: function (idx) {
			                return Math.random() * 200;
			            }
			        }
			    ]
			});
		},
		computed: {
			checkedAll: {
				get () {
					if(this.checkedCount == this.msgs.length){
						if(this.checkedCount == 0)
							return false;
						else
							return true;
					}
					return false;
				},
				set (value) {
					this.msgs.forEach((item) => {
						item.checked = value;
					});
					return value;
				}
			},
			checkedCount: {
				get () {
					let count = 0;
					this.msgs.forEach((item) => {
						if(item.checked)
							count++;
					});
					return count;
				}
			}
		},
		components: {
			'my-dialog': Dialog,
		}
	}
</script>

<style lang="scss" scoped>
	.index{
		width: 100%;
		&>.confirm{
			background-color: #fff;
		}
		&>h2{
			border-left: 4px solid #324157;
			padding-left: 10px;
			margin-bottom: 20px;
		}
		.container{
			&>.fit{
				float: left;
				border: 1px solid #d1dbe5;
			    border-radius: 4px;
			    background-color: #fff;
			    overflow: hidden;
			    box-shadow: 0 2px 4px 0 rgba(0,0,0,.12), 0 0 6px 0 rgba(0,0,0,.04);
			    box-sizing: border-box;
			    padding: 20px;
			    margin-right: 50px;
			    margin-bottom: 50px;
				&>.myCharts{
					
				}
				&>.taskList{
					height: 451px;
					&>hr{
						height: 1px;
					    border: none;
					    border-top: 1px dashed #ccc;
					    margin-bottom: 5px;
					    margin-top: 5px;
					}
					&>input{
						box-sizing: border-box;
						width: 100%;
						border-radius: 5px;
						line-height: 20px;
						font-size: 16px;
						border: 1px solid #ccc;
						margin-bottom: 5px;
						padding: 5px 12px;
						&:focus{
							border-color: #20A0FF;
							box-shadow: 0 0 2px #ccc;
						}
					}
					&>ul{
						width: 300px;
						border-top: 1px solid #ccc;
						border-right: 1px solid #ccc;
						border-radius: 5px;
						overflow: hidden;
						&>h6{
							font-size: 16px;
							text-align: center;
							line-height: 20px;
						    padding: 8px;
						    border-left: 1px solid #ccc;
						    border-bottom: 1px solid #ccc;
						}
						&>li{
							position: relative;
							transition: all 0.4s;
							border-left: 5px solid #209e91;
						    line-height: 20px;
						    padding: 8px;
						    border-bottom: 1px solid #ccc;
						    cursor: pointer;
						    overflow: hidden;
							&:hover{
								border-left: 30px solid #209e91;
	    						background-color: #eee;
	    						&>i{
	    							right: 5px;
	    						}
    						}
    						&>i{
    							transition: all 0.4s;
    							margin-right: 10px;
    							right: -30px;
    							top: 0;
    							line-height: 37px;
    							cursor: pointer;
    							position: absolute;
    							font-size: 20px;
    							color: red;
    							background-color: transparent;
    						}
						}
					}
				}
				&>.message{
					&>hr{
						height: 1px;
					    border: none;
					    border-top: 1px dashed #ccc;
					    margin-bottom: 5px;
					    margin-top: 5px;
					}
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
				}
			}
		}
	}
	input::-webkit-input-placeholder{
	    color: #ccc;
	}
</style>