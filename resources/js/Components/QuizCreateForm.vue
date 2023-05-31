<template>
  <form class="form" @submit.prevent="createQuiz">
    <input type="text" v-model="newQuiz.name" placeholder="quiz name"/>
    <QuizQestions @addQuestion="addQuestion" @addAnswer="addAnswer" />
    <va-button type="submit" class="my-1 btn mt-4"> Create </va-button>
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
        let result = this.validate();
        if (!result.success) {
            alert(result.error)
        }
        JSON.stringify(this.newQuiz);
        axios.put(`/api/quiz/`, JSON.stringify(this.newQuiz)).then((response) => {
          console.log(response);
        });
    },
    validate() {
        if ( !this.newQuiz.name) {
            return {
                success: false,
                error: 'Name field is required!'
            };
        }

        return {success: true};
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
