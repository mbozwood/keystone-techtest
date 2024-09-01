<template>
    <!--  FILTER BUTTONS FOR TAGS  -->
    <section class="px-4 py-4 mx-5 grid grid-cols-5 gap-2">
        <button
            v-for="tag in tags"
            @click="selectTag(tag)"
            type="button"
            :class="{ 'bg-blue-700':selectedTags.includes(tag) }"
            class="text-white bg-blue-400 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none"
        >{{ tag }}</button>
    </section>
    <!--  LINKS ARE SHOWN BELOW  -->
    <section class="px-4 py-4 mx-5 grid grid-cols-1 gap-4 items-center justify-center" v-if="filteredLinks.length">
        <div class="px-4 py-4 font-normal bg-gray-300 rounded-lg" v-for="link in filteredLinks">
            <div class="flex flex-col justify-between md:flex-row">
                <h3 class="mb-2 text-2xl font-semibold leading-snug">
                    {{ link.title }}
                </h3>
                <div class="flex items-center mb-2 space-x-2">
                    <p class="px-2 text-gray-200 bg-blue-600 rounded" v-for="tag in link.tags">#{{ tag.tag }}</p>
                </div>
            </div>
            <p class="text-gray-700">
                {{ link.comment }}
            </p>
        </div>
    </section>
    <section class="px-4 py-4 mx-5 grid grid-cols-1 gap-4 items-center justify-center" v-else>
        <h2>No Links matching selected tags found</h2>
    </section>
</template>

<script setup>
import axios from "axios";
import { ref } from "vue";

const links = ref([]);
const filteredLinks = ref([]);
const tags = ref(['laravel','vue','vue.js','php','api']);
const selectedTags = ref([]);

/*
    Retrieve the data from the API, and store the data in 2 locations.
    Links, which is used by later filter logic, and filteredLinks which is used to render within the view.
    We populate the filtered links here for default as we have not modified anything
 */
const getValue = async () => {
    try {
        const data = await axios.get("/api/links");
        links.value = data.data;
        filteredLinks.value = links.value;
    } catch (error) {
        // Do something with the error
        console.log(error);
    }
};

/*
    Logic to add and remove the tag from the array of selected item
    We also trigger the filter in here
 */
const selectTag = (tag) => {
    if(selectedTags.value.includes(tag)) {
        selectedTags.value = selectedTags.value.filter(item => item !== tag)
    } else {
        selectedTags.value.push(tag);
    }
    filter();
};

/*
    If we have no selected tags, show all links
    Otherwise, filter based on all selected tags.
    We use every to check whether all elements in an array pass the test implemented by the provided function
    In our case, the selected tags
 */
const filter = () => {
    if(selectedTags.value.length > 0) {
        // match all chosen tags
        filteredLinks.value = links.value.filter(l => l.valid_tags.every(tag => selectedTags.value.includes(tag)));
    } else {
        filteredLinks.value = links.value;
    }
};

getValue();
</script>
