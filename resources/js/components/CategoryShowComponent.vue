<script setup>
import { defineProps, onMounted, ref, watch} from "vue"
import { useRoute } from 'vue-router'

import { useStoreFlagger } from '../stores/flagger.js'
import { useDataSense } from '../stores/dataSense.js'

// import {} from '../../function.js'

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

        // *********************************************//
        // ***  tryの一行目におけるcategoryIDが存在せず  ***//
        // *** カテゴリを取得できないとき                ***//
        // *** カテゴリIDが最大のカテゴリを取得する。
        const reo = await axios.get('/api/max')
        categoryId = reo.data.id

        // categoryが存在しないidページにいる状態でかつ、他にカテゴリが存在せずidの最大値をとってきたときにそれがundefinedの場合
        if (categoryId == undefined) {
            console.log('test')
            noData.value = !noData.value
        }

        category.value = reo.data

        // **********************************************//
    }
}

const getMaxIdCategory = async () => {
    const res = await axios.get('/api/max')
    category.value = res.data

    categoryId = category.value.id
}

const getRecipes = async () => {
    try {
        const maxRes = await axios.get('/api/categories/' + categoryId + '/recipes/')
        recipes.value = maxRes.data
    } catch(err) {

        // console.log(err)
        // const maxRess = await axios.get('/api/categories/' + categoryId + '/recipes/')
        // recipes.value = maxRess.data
        // console.log(recipes.value)
    }
}

const submitNewRecipe = () => {
    axios.post('/api/categories/' + categoryId + '/recipes/store', {
        title: newRecipe.value
    })
}

onMounted(() => {
    getCategory()
        .then((data) => {
            /** thenメソッドの中にあるのはgetCategory()においてerrorが発生したときに
             *  もし仮にthenメソッドを用いず
             *
             *  getCategory()
             *  getRecipes()
             *  と記述した場合、getCategory()内のcatch()の中のcategoryIdに最大Idが入る前に
             *  getRecipesが実行されてidが最大のカテゴリに紐づくレシピを取得できない
             */
            getRecipes()
        })
        .catch((err) => {
            getMaxIdCategory()
        })
    // getRecipes()

})

// 別カテゴリを選択すると右ページ(CategoryShowComponent)が入れ替わる
watch(route, () => {
    categoryId = parseInt(route.params.categoryId)
    getCategory()
    getRecipes()
})



/** flagger:２つの役割
 *  ①現在CategoryShowComponentで表示しているカテゴリと同じカテゴリが削除されたのを感知して
 *    右ページにidが最大のカテゴリを取得して表示する
 *  ②左ページでカテゴリが追加されたのを感知して右ページにそのカテゴリを取得して表示させる
 *  ③ ②において取得したカテゴリに紐づくレシピを取得する
 */

flagger.$subscribe((mutation,state) => {
    getMaxIdCategory()
        .then((data) => {
            getRecipes()
        })
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
        <ul style="margin-top: 15px;">
            <li v-for="recipe in recipes">{{ recipe.title }}</li>
        </ul>
    </div>



</template>
