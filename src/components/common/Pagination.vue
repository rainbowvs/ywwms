<template>
	<div class="pagination">
		<ul>
			<li class="prev">
				<a :class="{ban:currentPage==1}" href="javascript:;" @click="toPrev($event)">&lt;</a>
			</li>
			<li v-for="page in pages" v-if="page.text!='...'" :class="{active:currentPage==page.text}">
				<a href="javascript:;" @click="selectPage($event,page.text)">{{page.text}}</a>
			</li>
			<li class="dot" v-else>...</li>
			<li class="next">
				<a :class="{ban:currentPage==totalPage}" href="javascript:;" @click="toNext($event)">&gt;</a>
			</li>
			<p>共 {{totalPage}} 页，到第 <input ref="inputPage" type="number" :value="toPage" :max="totalPage" min="1"/> 页</p>
			<button @click="submitPage">确定</button>
		</ul>
	</div>
</template>

<script>
	export default{
		props: {
			current: {
				//当前显示页码
				type: Number,
				default: 1
			},
			show: {
				//显示的页数[只能输入奇数,偶数最后一页无法显示]
				type: Number,
				default: 5
			},
			total: {
				//总页数
				type: Number,
				default: 1
			}
		},
		data () {
			return {
				currentPage: this.current,
				totalPage: this.total,
				showPage: this.show,
			}
		},
		watch: {
			current(val) {
				this.currentPage = val;
			},
			total(val) {
				this.totalPage = val;
			},
			show(val) {
				this.showPage = val;
			},
		},
		methods: {
			toPrev(e) {
				if(this.currentPage > 1){
					this.currentPage--;
					this.$emit('prev',this.currentPage);
				}
			},
			toNext(e) {
				if(this.currentPage < this.totalPage){
					this.currentPage++;
					this.$emit('next',this.currentPage);
				}
			},
			selectPage(e,page) {
				if(page > this.totalPage || page < 1 || this.currentPage == page)
					return false;
				else{
					this.currentPage = page;
					this.$emit('selectpage',this.currentPage);
				}
			},
			submitPage(e) {
				let oldValue = Number(this.$refs.inputPage.value);
				if(oldValue < 1 || oldValue > this.totalPage || (oldValue == this.currentPage))
					return false;
				else{
					this.currentPage = oldValue;
					this.$emit('submitpage',this.currentPage);
				}
			},
		},
		computed: {
			toPage() {
				if(this.currentPage < this.totalPage)
					return +this.currentPage+1;
				else
					return this.currentPage;
			},
			pages() {
				let len = this.totalPage;
				let temp = [];
				let list = [];
				let count = Math.floor(this.showPage / 2);
				let center = this.currentPage;
				
				if( len <= this.showPage ){
					while(len--){
						temp.push({
							text: this.totalPage-len,
							val: this.totalPage-len
						});
					};
					return temp;
				}
				
				while(len--){
					temp.push(this.totalPage - len);
				};
				
				let idx = temp.indexOf(center);   
				
				(idx < count) && (center = center + count - idx); 
				(this.currentPage > this.totalPage - count) && ( center = this.totalPage - count);
				
				temp = temp.splice(center - count -1, this.showPage);  
				
				do {
					let t = temp.shift();
					list.push({
						text: t,
						val: t
					});
				}while(temp.length);
				
				if(this.totalPage > this.showPage){
					(this.currentPage > count+1) && list.unshift({ text: '...',val: list[0].val-1 });
					(this.currentPage < this.totalPage-count) && list.push({ text: '...',val: list[list.length-1].val+1 });
				}
				return list;
			},
		}
	}
</script>

<style lang="scss" scoped>
	.pagination{
		margin: 5px 0;
		height: 33px;
		-webkit-user-select:none;
		&>ul{
			line-height: 31px;
			position: fixed;
			overflow: hidden;
			&>li{
				margin-left: -1px;
				float: left;
				border:1px solid #dfe6ec;
				position: relative;
				&>a{
					padding: 6px 10px;
					&.ban{
						cursor: default;
						color: #CCCCCC;
					}
				}
				&.prev{
					margin-left: 0;
				}
				&:hover{
					z-index: 1;
					border-color: #324157;
				}
				&.dot{
					padding: 1px 10px;
					cursor: default;
					border: 0;
				}
				&.active{
					border-color: #324157;
					background-color: #324157;
					&>a{
						cursor: default;
						color: #fff;
					}
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
</style>