<template>
  <form class="form" @submit.prevent>
    <input type="text" v-model="name" placeholder="quiz name" :disabled="showQestions" />
    <va-button @click="createQuiz" v-if="name && !showQestions" class="my-1 btn mt-4">
      Create
    </va-button>
    <QuizQestions v-if="showQestions" @addQuestion="addQuestion" @addAnswer="addAnswer" />
  </form>
</template>
<script>
import QuizQestions from "@/Components/QuizQestions.vue";
import { result } from "lodash";

export default {
  components: {
    QuizQestions,
  },
  data() {
    return {
      name: "",
      showQestions: false,
      createdQuizId: '',
    };
  },
  methods: {
    addQuestion(question) {
      question.quiz_id = this.createdQuizId;
      console.log(question);
      axios.put(`/api/question/`,question).then((response) => {
        this.showQestions = true;
      });
    },
    addAnswer(answer) {
      console.log(answer);
    },
    createQuiz() {
      axios.put(`/api/quiz/`, { name: this.name }).then((response) => {
        this.createdQuizId = response.data.id;
        this.showQestions = true;

        this.$emit('createQuiz', response.data);
      });
    },
    validate() {
      if (!this.newQuiz.name) {
        return {
          success: false,
          error: "Name field is required!",
        };
      }

      return { success: true };
    },
  },
};
</script>
<style scoped>
.form {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 700px;
}
input {
  width: 100%;
}
.btn {
  width: 100%;
}
</style>
