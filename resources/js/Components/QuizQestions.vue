<template>
  <div class="questions">
    <div class="title">
      Questions
      <va-button class="my-1 btn" icon="add" @click="addNewQuestion"> </va-button>
    </div>
    <div v-for="(q, k) in questions">
      <div class="question">
        <div class="name">{{ q.name }}</div>
        <va-button class="btn" icon="add" @click="showCreateAnswer = k"> </va-button>
      </div>
      <div class="answers ml-10">
        <div v-for="answer in q.answers" class="answer">
          {{ answer }}
        </div>
        <div v-if="showCreateAnswer === k" class="createAnswer">
          <input type="text" v-model="answer" placeholder="answer" />
          <va-button class="btn" icon="add" @click="addAnswer(k)"> </va-button>
        </div>
      </div>
    </div>
  </div>

  <div v-if="showCreateQuestion" class="createQuestion">
    <input v-model="name" type="text" placeholder="question" />
    <va-button class="my-1 btn mt-4" icon="add" @click="addQuestion(k)">
      add question
    </va-button>
  </div>
</template>
<script>
export default {
  emits: ["addQuestion", "addAnswer"],
  data() {
    return {
      name: "",
      answer: '',
      questions: [],
      showCreateQuestion: false,
      showCreateAnswer: false,
    };
  },
  methods: {
    addNewQuestion() {
      this.showCreateQuestion = true;
      this.showCreateAnswer = false;
    },
    addQuestion() {
      const question = {
        name: this.name,
        answers: [],
      };

      this.questions.push(question);
      this.$emit('addQuestion', question);
      this.showCreateQuestion = false;
      this.name = "";
    },
    addAnswer(k) {
      console.log('addAnswer');
      this.$emit('addAnswer', k, this.answer);
      this.answer = '';
    },
  },
};
</script>
<style scoped>
.title {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 10px;
  width: 100%;
}
.createQuestion {
  display: flex;
  flex-direction: column;
  width: 100%;
}
.btn {
  align-self: start;
}
.questions {
  width: 100%;
}
.question {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
  font-weight: 700;
  font-size: 20px;
}
.answer {
  display: flex;
  align-items: center;
}
.createAnswer {
  display: flex;
  align-items: center;
  gap: 10px;
}
</style>
