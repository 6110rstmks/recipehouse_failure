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
    try {
        const res = await axios.get('/api/categories/' + categoryId)
        console.log(res.data)
        category.value = res.data
    } catch(err) {
        noData.value = !noData.value
    }

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

// 残り一つのカテゴリが削除されたのを感知する
dataSense.$subscribe((mutation, state) => {
    noData.value = !noData.value
})

</script>

<template>
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
