<template>
  <form class="form" @submit.prevent>
    <div class="flex items-start flex-col w-full">
      <label for="quiz_name">Enter the quiz name</label>
      <input
        id="quiz_name"
        type="text"
        v-model="name"
        placeholder="quiz name"
        :disabled="showQuestions"
      />
    </div>

    <va-button @click="createQuiz" v-if="name && !showQuestions" class="my-1 btn mt-4">
      Create
    </va-button>
    <QuizQestions v-if="showQuestions" @addQuestion="addQuestion" @addVariant="addVariant" />
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
      showQuestions: false,
      createdQuizId: "",
    };
  },
  methods: {
    addQuestion(question) {
      question.quiz_id = this.createdQuizId;
      console.log(question);
      axios.put(`/api/question/`, question).then((response) => {
        this.showQuestions = true;
      });
    },
    addVariant(variant) {
      console.log(variant);
    },
    createQuiz() {
      axios.put(`/api/quiz/`, { name: this.name }).then((response) => {
        this.createdQuizId = response.data.id;
        this.showQuestions = true;

        this.$emit("createQuiz", response.data);
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
