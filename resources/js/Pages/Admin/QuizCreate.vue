<template>
        <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Quiz</h2>
        </template>

         <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <CreateQuizForm />
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import CreateQuizForm from "@/Components/QuizCreateForm.vue"

export default {
    components: {
        AppLayout,
        CreateQuizForm
    }
    
}
</script>
<style>
    
</style>