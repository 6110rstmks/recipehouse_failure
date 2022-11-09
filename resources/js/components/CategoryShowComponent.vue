<script setup>
import { defineProps, onMounted, ref, watch} from "vue"
import { useRoute } from 'vue-router'

import { useStoreFlagger } from '../stores/flagger.js'
const flagger = useStoreFlagger()

const route = useRoute()

const props = defineProps({
  categoryId: String,
})

const category = ref({})

const recipes = ref([])

const newRecipe = ref('')

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
            console.log(recipes.value)

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

    let flagData = ref(flagger.flag)

    watch(flagData, () => {
        getMaxIdCategory()
    })
})

watch(route, () => {
    categoryId = parseInt(route.params.categoryId)
    getCategory()
    getRecipes()
})

// 個別ページと同じカテゴリが削除されたのを感知してidが最大のページを取得する
flagger.$subscribe((mutation,state) => {
    getMaxIdCategory()
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
        <ul style="margin-top: 15px;">
            <li v-for="recipe in recipes">{{ recipe.title }}</li>
        </ul>

</template>
