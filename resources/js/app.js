// localで確認するときは、php artisan serve とnpm run devを実行してください
// 久しぶりに確認するときは忘れがち。
import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler'
import * as VueRouter from 'vue-router'
import { createPinia } from 'pinia'

import CategoryListComponent from './components/CategoryListComponent.vue'
import CategoryShowComponent from './components/CategoryShowComponent.vue'
import CategoryShowHomeComponent from './components/CategoryShowHomeComponent.vue'


const routes = [
    {
        path: '/',
        name: 'category.list',
        components: {
            Home: CategoryShowHomeComponent
        }
    },
    {
        path: '/categories/:categoryId',
        name: 'category.show',
        components: {
            Show: CategoryShowComponent
        },
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
