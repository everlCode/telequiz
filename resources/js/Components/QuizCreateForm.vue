<template>
  <form class="form" @submit.prevent="createQuiz">
    <input type="text" v-model="newQuiz.name" />
    <QuizQestions @addQuestion="addQuestion" @addAnswer="addAnswer" />
    <va-button type="submit" class="my-1 btn mt-4"> Create </va-button>
  </form>
</template>
<script>
import QuizQestions from "@/Components/QuizQestions.vue";

export default {
  components: {
    QuizQestions,
  },
  data() {
    const newQuiz = {
      name: "",
      questions: [],
    };

    return {
      newQuiz,
    };
  },
  methods: {
    addQuestion(question) {
      this.newQuiz.questions.push(question);
    },
    addAnswer(key, answer) {
        this.newQuiz.questions[key].answers.push(answer)
    },
    createQuiz() {
        if (!this.validate()) {
            alert('errror');

            return false;
        }
        JSON.stringify(this.newQuiz);
    },
    validate() {
        if ( !this.newQuiz.name) {
            return false;
        }

        return true;
    }
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
