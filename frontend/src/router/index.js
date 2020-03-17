import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'

import CustomerIndex from '../views/Customers/Index.vue'
import CustomerList from '../views/Customers/List.vue'
import CustomerNew from '../views/Customers/New.vue'
import CustomerEdit from '../views/Customers/Edit.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/customers',
    component: CustomerIndex,
    children: [
      {path: '', component: CustomerList, props: true, name: 'customers.index'},
      {path: 'new', component: CustomerNew, props: true, name: 'customers.new'},
      {path: 'edit/:id', component: CustomerEdit, props: true, name: 'customers.edit'},
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router