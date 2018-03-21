import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
//	mode: 'history',
  routes: [
    {
      path: '/',
      component: () => import('@/components/common/Home'),
      children: [
     		{
          path:'',
          name: 'Index',
          component: () => import('@/components/page/Index'),
          meta: {
		      	requireProp: true,
		      },
        },
      	{
      		path: '/order',
		      name: 'Order',
		      component: () => import('@/components/page/Order'),
		      meta: {
		      	requireProp: true,
		      },
      	},
      	{
      		path: '/shop',
		      name: 'Shop',
		      component: () => import('@/components/page/Shop'),
		      meta: {
		      	requireProp: true,
		      },
      	},
      	{
      		path: '/administrator',
		      name: 'Administrator',
		      component: () => import('@/components/page/Administrator'),
		      meta: {
		      	requireProp: true,
		      },
      	},
      	{
      		path: '/user',
		      name: 'User',
		      component: () => import('@/components/page/User'),
		      meta: {
		      	requireProp: true,
		      },
      	},
      ],
    },
    {
			path: '/login',
	    name: 'Login',
	    component: () => import('@/components/page/Login'),
		},
  ]
});

export default router;