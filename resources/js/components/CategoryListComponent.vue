<script setup>
import { ref, onMounted, watch } from "vue"
import { useRoute, useRouter} from 'vue-router'

import { useStoreFlagger } from '../stores/flagger.js'
import { useDataSense } from '../stores/dataSense.js'

const flagger = useStoreFlagger()

const dataSense = useDataSense()

const route = useRoute()


const newCategory = ref('')

const categories = ref([])

let currentCategoryId = ref('')

let currentUrl = ref('')


const submitNewCategory = () => {
    axios.post('/api/categories/store', {
        title: newCategory.value
    })
}

const getCurrentCategoryId = () => {

// window.location.href returns the href (URL) of the current page ·
    // currentUrl = ref(window.location.href)
    currentUrl = ref(window.location.href)
    currentCategoryId = ref(currentUrl.value.replace('http://localhost:8000/categories/', ''))
}

const addCategory = async () => {
    if (categories.value.length === 0)
    {
        dataSense.flag = !dataSense.flag
    }
    submitNewCategory()
    getCategories()
    newCategory.value = ''
    flagger.flag = !flagger.flag
}

const getCategories = async () => {
    axios.get('/api/categories')
        .then((res) => {
        categories.value = res.data
    })
}

const deleteCategory = async (id) => {
    //　(CategoryShow用のバグ処理)
    //  カテゴリが一件しかない状態でそれを削除したら
    //  CategoryShowComponentでNo Categoryと表示させる
    if (categories.value.length === 1) {
        dataSense.flag = !dataSense.flag
    }

    // 最大idカテゴリが削除されたら
    // 改めてそれよりidが小さいカテゴリの中で最大のカテゴリを取得する
    // (CategoryShowHome(デフォルトページ)用のバグ処理)
    const res = await axios.get('/api/max')
    let MaxCategoryId = res.data.id
    if (MaxCategoryId === id)
    {
        flagger.flag = !flagger.flag
    }

    await axios.delete('/api/categories/' + id)
    if (id == currentCategoryId.value) {
        flagger.flag = !flagger.flag
    }
    getCategories()
}

onMounted(() => {
    getCurrentCategoryId()
    getCategories()

})

watch(route, () => {
    getCurrentCategoryId()
})
</script>

<template>
    <div class="form-box">
        <h4 style="margin-bottom: 20px; margin-top: 10px">RECIPE HOUSE</h4>
        <form method="post" v-on:submit.prevent="addCategory">
            <input type="text" v-model="newCategory">
            <!-- <input type="text" v-model="newCategory" v-on:keydown.enter="$router.push({ path: '/'})"> -->
        </form>
    </div>
    <ul class="category_ul">
        <li v-for="category in categories" :key="category.id">
            <button class="btn btn-danger" v-on:click="deleteCategory(category.id)">Delete</button>
            <router-link :to="{name: 'category.show', params: {categoryId: category.id }}">
                <span>{{ category.title }}</span>
            </router-link>
        </li>
    </ul>

</template>

