<script setup>
import { defineProps, onMounted, ref, watch} from "vue"
import { useRoute } from 'vue-router'

import { useStoreFlagger } from '../stores/flagger.js'
import { useDataSense } from '../stores/dataSense.js'


const flagger = useStoreFlagger()
const dataSense = useDataSense()

const route = useRoute()

const props = defineProps({
  categoryId: String,
})

const category = ref({})

const recipes = ref([])

const newRecipe = ref('')

const noData = ref(false)

let categoryId = parseInt(route.params.categoryId)

const addRecipe = () => {
    submitNewRecipe()
    getRecipes()
    newRecipe.value = ''
}

const getCategory = async () => {
    axios.get('/api/categories/' + categoryId)
        .then((res) => {
            category.value = res.data
        })
}

const getMaxIdCategory = () => {
    axios.get('/api/max')
        .then((res) => {
            category.value = res.data
        })
}

const getRecipes = () => {
    axios.get('/api/categories/' + categoryId + '/recipes/')
        .then((res) => {
            recipes.value = res.data
        })
}

const submitNewRecipe = () => {
    axios.post('/api/categories/' + categoryId + '/recipes/store', {
        title: newRecipe.value
    })
}

onMounted(() => {
    getCategory()
    getRecipes()
})

watch(route, () => {
    categoryId = parseInt(route.params.categoryId)
    getCategory()
    getRecipes()
})

// ２つの役割
// ①個別ページと同じカテゴリが削除されたのを感知してidが最大のページを取得して表示する
// ②左ページでカテゴリが追加されたのを感知して右ページにそのカテゴリを取得して表示させる

flagger.$subscribe((mutation,state) => {
    getMaxIdCategory()
})

dataSense.$subscribe((mutation, state) => {
    noData.value = true
})

</script>

<template>
    <span class="icon">
        <i class="fas fa-utensils fa-lg"></i>
    </span>
    <div>{{ category.title }}</div>
    <form method="post" v-on:submit.prevent="addRecipe">
        <input type="text" v-model="newRecipe">
    </form>

    <li v-if="noData">No data</li>
    <ul style="margin-top: 15px;" v-if="!noData">
        <li v-for="recipe in recipes">{{ recipe.title }}</li>
    </ul>
</template>
