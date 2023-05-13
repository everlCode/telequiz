<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Photos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-2xl">Add a new Photo</h1>
                <!-- 👇 Photo creation form goes here -->

            </div>
        </div>
    </app-layout>
</template>


<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

export default defineComponent({
    components: {
        AppLayout,
    },

});
</script>