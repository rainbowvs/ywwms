import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/common/Home'
import Index from '@/components/page/Index'
import Order from '@/components/page/Order'
import Shop from '@/components/page/Shop'
import Administrator from '@/components/page/Administrator'
import User from '@/components/page/User'
import Login from '@/components/page/Login'


Vue.use(Router)

export default new Router({
//	mode: 'history',
  routes: [
    {
      path: '/',
      component: Home,
      children: [
     		{
          path:'',
          name: 'Index',
          component: Index,
        },
      	{
      		path: '/order',
		      name: 'Order',
		      component: Order,
      	},
      	{
      		path: '/shop',
		      name: 'Shop',
		      component: Shop,
      	},
      	{
      		path: '/administrator',
		      name: 'Administrator',
		      component: Administrator,
      	},
      	{
      		path: '/user',
		      name: 'User',
		      component: User,
      	},
      ]
    },
    {
			path: '/login',
	    name: 'Login',
	    component: Login,
		},
  ]
})
