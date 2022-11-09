import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler'
import * as VueRouter from 'vue-router'
import { createPinia } from 'pinia'

import CategoryListComponent from './components/CategoryListComponent.vue'
import CategoryShowComponent from './components/CategoryShowComponent.vue'
import CategoryMaxComponent from './components/CategoryMaxComponent.vue'


const routes = [
    {
        // path: '/categories',
        path: '/',
        name: 'category.list',
        components: {
            Max: CategoryMaxComponent
        }
    },
    {
        path: '/categories/:categoryId',
        name: 'category.show',
        component: CategoryShowComponent,
        props: true
    },
]

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes,
})

const app = createApp({})
app.component('category-list', CategoryListComponent)
app.use(router)
app.use(createPinia())
app.mount('#app')
