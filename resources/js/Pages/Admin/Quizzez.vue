<template>
  <app-layout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quizzez</h2>
    </template>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <!-- <MyTable :items="quizzez"/> -->
      <MyQuizTable :quizzez="quizzez" :headers="headers" @openAddModal="openAddModal" @deleteQuiz="deleteQuiz" @addQuiz="addQuiz"/>
    </div>
  </app-layout>
</template>

<style>
body {
  font-family: var(--font-family);
}
</style>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import MyTable from "@/Components/MyTable.vue";
import MyQuizTable from "../../Components/MyQuizTable.vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";

export default defineComponent({
  components: {
    AppLayout,
    MyTable,
    MyQuizTable,
  },
  props: {
    quizzez: Array,
  },
  setup(props) { },
  data() {
    const headers = { id: "id", name: "name" };

    return {
      headers,
    };
  },
  methods: {
    addQuiz() {
      this.$router.push('/posts')
    },
    openAddModal() {
      alert("modal");
    },
    deleteQuiz(id) {
      axios.delete(`/api/quiz/${id}`).then(response => {
        
      })
    }
  },
});
</script>
