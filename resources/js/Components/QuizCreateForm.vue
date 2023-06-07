<template>
  <form class="form" @submit.prevent>
    <div class="flex items-start flex-col w-full">
      <label for="quiz_name">Enter the quiz name</label>
      <input
        v-on:keyup.enter="createQuiz"
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
    questions: {
      type: Array,
      default: [],
    },
  },
  data() {
    return {
      name: "",
      data: this.questions,
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
      axios.delete(`/api/question/${id}`).then(response => {
        this.data.splice(k, 1)
      })
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
