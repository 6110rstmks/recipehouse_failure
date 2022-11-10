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
    submitNewCategory()
    getCategories()
    newCategory.value = ''
    flagger.flag = !flagger.flag
}

const getCategories = async () => {
    axios.get('/api/categories')
        .then((res) => {
        categories.value = res.data
        console.log(categories.value)
    })
}

const deleteCategory = (id) => {
    if (categories.value.length === 1) {
        dataSense.flag = !dataSense.flag
        console.log(dataSense.flag)
    }
    axios.delete('/api/categories/' + id)
        .then(() => {
            if (id == currentCategoryId.value) {
                flagger.flag = !flagger.flag
            }
            getCategories()
    })
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

