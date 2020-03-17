import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'

import CustomerIndex from '../views/Customers/Index.vue'
import CustomerList from '../views/Customers/List.vue'
import CustomerNew from '../views/Customers/New.vue'
import CustomerEdit from '../views/Customers/Edit.vue'

import SuplierIndex from '../views/Supliers/Index.vue'
import SuplierList from '../views/Supliers/List.vue'
import SuplierNew from '../views/Supliers/New.vue'
import SuplierEdit from '../views/Supliers/Edit.vue'

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
  {
    path: '/supliers',
    component: SuplierIndex,
    children: [
      {path: '', component: SuplierList, props: true, name: 'supliers.index'},
      {path: 'new', component: SuplierNew, props: true, name: 'supliers.new'},
      {path: 'edit/:id', component: SuplierEdit, props: true, name: 'supliers.edit'},
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router