<script setup>

import { ref, onMounted } from "vue"

const category = ref({})
const newRecipe = ref('')
const recipes = ref([])

let categoryId = ref('')

const addRecipe = () => {
    submitNewRecipe()
    console.log('lo')
    getRecipes()
    newRecipe.value = ''
}

const getRecipes = () => {
    console.log('toru')
    console.log(category.value.id)
    axios.get('/api/categories/' + categoryId + '/recipes/')
        .then((res) => {
            console.log(res.data)
            recipes.value = res.data
    })
}

const submitNewRecipe = () => {
    axios.post('/api/categories/' + categoryId + '/recipes/store', {
        title: newRecipe.value
    })
}

const getMaxCategory = () => {
    axios.get('/api/max')
        .then((res) => {
        category.value = res.data
        console.log(category.value.id)
    })
}

onMounted(() => {
    getMaxCategory()
    getRecipes()
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
