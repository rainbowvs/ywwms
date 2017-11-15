import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
//	mode: 'history',
  routes: [
    {
      path: '/',
      component: resolve => require(['@/components/common/Home'], resolve),
      children: [
     		{
          path:'',
          name: 'Index',
          component: resolve => require(['@/components/page/Index'], resolve),
        },
      	{
      		path: '/order',
		      name: 'Order',
		      component: resolve => require(['@/components/page/Order'], resolve),
      	},
      	{
      		path: '/shop',
		      name: 'Shop',
		      component: resolve => require(['@/components/page/Shop'], resolve),
      	},
      	{
      		path: '/administrator',
		      name: 'Administrator',
		      component: resolve => require(['@/components/page/Administrator'], resolve),
      	},
      	{
      		path: '/user',
		      name: 'User',
		      component: resolve => require(['@/components/page/User'], resolve),
      	},
      ]
    },
    {
			path: '/login',
	    name: 'Login',
	    component: resolve => require(['@/components/page/Login'], resolve),
		},
  ]
})
