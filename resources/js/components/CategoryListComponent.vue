<script setup>
    import { ref, onMounted, watch } from "vue"
    import { useRoute } from 'vue-router'

    import { useStoreFlagger } from '../stores/flagger.js';
    const flagger = useStoreFlagger();


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

    const addCategory = () => {
        submitNewCategory()
        getCategories()
        newCategory.value = ''
    }

    const getCategories = () => {
        axios.get('/api/categories')
            .then((res) => {
            categories.value = res.data
        })
    }

    const deleteCategory = (id) => {
        axios.delete('/api/categories/' + id)
            .then((res) => {
                if (id == currentCategoryId.value) {
                    flagger.flag = !flagger.flag
                }
                getCategories()
        })
        // console.log(window.history.back())
    }

    onMounted(() => {
        getCurrentCategoryId()
        getCategories()
        console.log(categories.value)
    })

    watch(route, () => {
        getCurrentCategoryId()
    })


</script>

<template>
    <div class="form-box">
        <h4 style="margin-bottom: 20px; margin-top: 10px">RECIPE HOUSE</h4>
        <!-- <form method="post" action="{{ route('categories.store') }}">
            @csrf
            <p class="control has-icons-left has-icons-right">
                    <input class="input title-input" type="text" name="title" placeholder="enter category name">
                    <span class="icon is-small is-left">
                        <i class="fas fa-utensils"></i>
                    </span>
            </p>
        </form> -->
        <form method="post" v-on:submit.prevent="addCategory">
            <input type="text" v-model="newCategory">
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

