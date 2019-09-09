import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

var router = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/Home.vue'),
    children: [
      {
        path: '/index',
        name: 'index',
        component: () => import('@/views/Index.vue')
      },
      {
        path: '/product',
        name: 'product',
        component: () => import('@/views/Product.vue')
      },
      {
        path: '/video',
        name: 'video',
        component: () => import('@/views/Video.vue')
      },
      {
        path: '/news',
        name: 'news',
        component: () => import('@/views/News.vue')
      },
      {
        path: '/view',
        name: 'view',
        component: () => import('@/views/View.vue')
      },
      {
        path: '*',
        name: 'error',
        component: () => import('@/views/Error.vue'),
        redirect:'index',//在children的后面加一个redirect：'/想要默认展示的子路由名字'
      },
    ]
  },
]

export default new Router({
  // base: process.env.publicPath,
  routes: router
})
