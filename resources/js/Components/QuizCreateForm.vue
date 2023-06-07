<template>
  {{ quiz }} 
  <form class="form" @submit.prevent>
    <div class="flex items-start flex-col w-full">
      <label v-if="!createdQuizId" for="quiz_name">Enter the quiz name</label>
      <div class="flex justify-between gap-2 w-full">
        <input
          v-on:keyup.enter="createQuiz"
          @change="editQuiz"
          id="quiz_name"
          type="text"
          v-model="name"
          placeholder="quiz name"
        />
      </div>
    </div>

    <va-button @click="createQuiz" v-if="name && !showQuestions" class="my-1 btn mt-4">
      Create
    </va-button>
    <QuizQestions
      v-if="showQuestions"
      :questions="data"
      @addQuestion="addQuestion"
      @addVariant="addVariant"
      @removeQuestion="removeQuestion"
      @removeVariant="removeVariant"
    />
  </form>
</template>
<script>
import QuizQestions from "@/Components/QuizQestions.vue";

export default {
  components: {
    QuizQestions,
  },
  props: {
    quiz: Object,
  },
  data() {
    return {
      name: this.quiz?.name ? this.quiz.name : '',
      data: this.quiz,
      showQuestions: false,
      createdQuizId: "",
    };
  },
  methods: {
    addQuestion(question) {
      question.quiz_id = this.createdQuizId;
      axios.put(`/api/question/`, question).then((response) => {
        response.data.variants = [];
        this.data.push(response.data);
        this.showQuestions = true;
      });
    },
    removeQuestion(id, k) {
      axios.delete(`/api/question/${id}`).then((response) => {
        this.data.splice(k, 1);
      });
    },
    addVariant(k, variant) {
      variant.is_right = true;

      axios.put(`/api/variant/`, variant).then((response) => {
        this.data[k].variants?.push(response.data);
      });
    },
    removeVariant(question_key, variant_key) {
      console.log(this.data[question_key].variants.splice(variant_key, 1));
      console.log(variant_key);
      // axios.delete(`/api/variant/${id}`).then(response => {
      //   this.data[k]?.variants[id];

      // })
    },
    createQuiz() {
      if (!this.name) {
        alert("Fill the quiz name!");
      }

      axios.put(`/api/quiz/`, { name: this.name }).then((response) => {
        this.createdQuizId = response.data.id;
        this.showQuestions = true;
        this.isQuizNameInputDisabled = true;
        this.$emit("createQuiz", response.data);
      });
    },
    editQuiz() {
      if (!this.createdQuizId || !this.name) {
        return;
      }
      let data = { name: this.name, id: this.createdQuizId };

      axios.post(`/api/quiz/`, data).then((response) => {
        this.isQuizNameChanged = true;
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
