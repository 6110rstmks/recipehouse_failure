<script setup>

import { ref, onMounted } from "vue"

import { useStoreFlagger } from '../stores/flagger.js'

const flagger = useStoreFlagger()

const category = ref({})
const newRecipe = ref('')
const recipes = ref([])

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
    category.value = maxRes.data
    let categoryId = category.value.id
    axios.get('/api/categories/' + categoryId + '/recipes/')
        .then((res) => {
            recipes.value = res.data
    })
}

onMounted(() => {
    onMountedGetRecipes()
})

flagger.$subscribe((mutation,state) => {
    getMaxIdCategory()
})

</script>

<template>
    <!-- <div>{{ category && category[0] ? category[0].title : '' }}</div>] -->
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
