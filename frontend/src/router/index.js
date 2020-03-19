import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'

import ScheduleList from '../views/Schedule/List.vue'

import CustomerIndex from '../views/Customers/Index.vue'
import CustomerList from '../views/Customers/List.vue'
import CustomerNew from '../views/Customers/New.vue'
import CustomerEdit from '../views/Customers/Edit.vue'

import SuplierIndex from '../views/Supliers/Index.vue'
import SuplierList from '../views/Supliers/List.vue'
import SuplierNew from '../views/Supliers/New.vue'
import SuplierEdit from '../views/Supliers/Edit.vue'

import TeamIndex from '../views/Team/Index.vue'
import TeamList from '../views/Team/List.vue'
import TeamNew from '../views/Team/New.vue'
import TeamEdit from '../views/Team/Edit.vue'

import ProductIndex from '../views/Products/Index.vue'
import ProductList from '../views/Products/List.vue'
import ProductNew from '../views/Products/New.vue'
import ProductEdit from '../views/Products/Edit.vue'

import EventIndex from '../views/Events/Index.vue'
import EventList from '../views/Events/List.vue'
import EventNew from '../views/Events/New.vue'
import EventEdit from '../views/Events/Edit.vue'

import SalesIndex from '../views/Sales/Index.vue'
import SalesList from '../views/Sales/List.vue'
import SalesNew from '../views/Sales/New.vue'
import SalesEdit from '../views/Sales/Edit.vue'

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
    path: '/schedule',
    name: 'schedule',
    component: ScheduleList
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
  {
    path: '/team',
    component: TeamIndex,
    children: [
      {path: '', component: TeamList, props: true, name: 'team.index'},
      {path: 'new', component: TeamNew, props: true, name: 'team.new'},
      {path: 'edit/:id', component: TeamEdit, props: true, name: 'team.edit'},
    ]
  },
  {
    path: '/products',
    component: ProductIndex,
    children: [
      {path: '', component: ProductList, props: true, name: 'products.index'},
      {path: 'new', component: ProductNew, props: true, name: 'products.new'},
      {path: 'edit/:id', component: ProductEdit, props: true, name: 'products.edit'},
    ]
  },
  {
    path: '/events',
    component: EventIndex,
    children: [
      {path: '', component: EventList, props: true, name: 'events.index'},
      {path: 'new', component: EventNew, props: true, name: 'events.new'},
      {path: 'edit/:id', component: EventEdit, props: true, name: 'events.edit'},
    ]
  },
  {
    path: '/sales',
    component: SalesIndex,
    children: [
      {path: '', component: SalesList, props: true, name: 'sales.index'},
      {path: 'new', component: SalesNew, props: true, name: 'sales.new'},
      {path: 'edit/:id', component: SalesEdit, props: true, name: 'sales.edit'},
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router