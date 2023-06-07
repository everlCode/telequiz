<template>  
  <app-layout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Quiz</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <QuizCreateForm :quiz="data" />
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import QuizCreateForm from "@/Components/QuizCreateForm.vue";

export default {
  props: {
    quiz: {
        type: Object,
        default: [],
    }
  },
  data() {
    return {
        data: this.quiz
    }
  },
  components: {
    AppLayout,
    QuizCreateForm,
  },
};
</script>
<style></style>
