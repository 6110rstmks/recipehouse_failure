<script setup>

import { ref, onMounted } from "vue"

import { useStoreFlagger } from '../stores/flagger.js'
import { useDataSense } from '../stores/dataSense.js'


const flagger = useStoreFlagger()
const dataSense = useDataSense()


const category = ref({})
const newRecipe = ref('')
const recipes = ref([])
const noData = ref(true)

const addRecipe = () => {
    submitNewRecipe()
    getRecipes()
    newRecipe.value = ''
}

const submitNewRecipe = () => {
    let categoryId = category.value.id
    axios.post('/api/categories/' + categoryId + '/recipes/store', {
        title: newRecipe.value
    })
}

const getRecipes = () => {
    let categoryId = category.value.id

    axios.get('/api/categories/' + categoryId + '/recipes/')
        .then((res) => {
            console.log(res.data)
            recipes.value = res.data
    })
}

const getMaxIdCategory = async () => {
    const maxRes = await axios.get('/api/max')
    category.value = maxRes.data
}

const onMountedGetRecipes = async () => {
    const maxRes = await axios.get('/api/max')
    // カテゴリが一つもない場合
    if (maxRes.data === '')
    {
        return
    }
    noData.value = !noData.value
    category.value = maxRes.data
    let categoryId = category.value.id
    const recipesRes = await axios.get('/api/categories/' + categoryId + '/recipes/')
    recipes.value = recipesRes.data
}

onMounted(() => {
    onMountedGetRecipes()
})

flagger.$subscribe((mutation,state) => {
    getMaxIdCategory()
})

dataSense.$subscribe((mutation, state) => {
    noData.value = !noData.value
})

</script>

<template>
    <!-- <div>{{ category && category[0] ? category[0].title : '' }}</div>] -->
    <span class="icon">
            <i class="fas fa-utensils fa-lg"></i>
        </span>
    <div v-if="noData">No Category</div>
    <div v-if="!noData">
        <span>{{ category.title }}</span>
        <form method="post" v-on:submit.prevent="addRecipe">
            <input type="text" v-model="newRecipe">
        </form>
    </div>

    <ul style="margin-top: 15px;">
        <li v-for="recipe in recipes">{{ recipe.title }}</li>
    </ul>


</template>
