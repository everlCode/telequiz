<template>
  
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

    <va-button @click="createQuiz" v-if="!createdQuizId" class="my-1 btn mt-4">
      Create
    </va-button>

    <QuizQestions
      :questions="data.questions"
      @addQuestion="addQuestion"
      @addVariant="addVariant"
      @removeQuestion="removeQuestion"
      @removeVariant="removeVariant"
    />
    <Link :href="route('admin.quizzez')">
      <va-button class="my-1 btn mt-4" > Ok</va-button>
    </Link>
  </form>
</template>
<script>
import QuizQestions from "@/Components/QuizQestions.vue";
import { Link } from "@inertiajs/vue3";

export default {
  components: {
    QuizQestions,
    Link
  },
  props: {
    quiz: Object,
  },
  data() {
    return {
      name: this.quiz?.name ? this.quiz.name : '',
      data: this.quiz,
      showQuestions: false,
      createdQuizId: this.quiz?.id ? this.quiz.id : null,
    };
  },
  methods: {
    addQuestion(question) {
      question.quiz_id = this.createdQuizId;
      axios.put(`/api/question/`, question).then((response) => {
        response.data.variants = [];
        this.data.questions.push(response.data);
        this.showQuestions = true;
      });
    },
    removeQuestion(id, k) {
      axios.delete(`/api/question/${id}`).then((response) => {
        this.data.questions.splice(k, 1);
      });
    },
    addVariant(k, variant) {
      variant.is_right = false;

      axios.put(`/api/variant/`, variant).then((response) => {
        this.data.questions[k].variants?.push(response.data);
      });
    },
    removeVariant(question_key, variant_key) {
      console.log(this.data.questions[question_key].variants[variant_key].id);
      let id = this.data.questions[question_key].variants[variant_key].id;
      axios.delete(`/api/variant/${id}`).then(response => {
        this.data.questions[question_key].variants.splice(variant_key, 1)
      })
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
}
input {
  width: 100%;
}
.btn {
  width: 100%;
}
</style>
