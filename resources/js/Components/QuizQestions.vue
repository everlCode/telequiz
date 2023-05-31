<template>
  <div class="questions">
    <div class="question_block" v-for="(q, k) in questions">
      <div class="question">
        <div class="name">{{ q.name }}</div>
        <va-button class="btn" icon="add" @click="showCreateAnswer = k"> </va-button>
      </div>
      <span v-if="q.answers.length > 0" class="variants">Variants:</span>
      <div class="answers ml-10">
        <div v-for="answer in q.answers" class="answer">
          {{ answer }}
        </div>
        <div v-if="showCreateAnswer === k" class="createAnswer">
          <label for="answer">Answer name:</label>
          <input type="text" id="answer" v-model="answer" placeholder="answer" />
          <va-button v-if="answer" class="btn" icon="add" @click="addAnswer(k)">
          </va-button>
        </div>
      </div>
    </div>
  </div>
 

  <div v-if="showCreateQuestion" class="createQuestion mt-5">
  <label for="question">Question name:</label>
    <input v-model="name" id="question" type="text" placeholder="question" />
    <va-button class="my-1 btn mt-4" icon="add" @click="addQuestion(k)">
      create
    </va-button>
  </div>

  <div class="title mt-10">
    <va-button class="my-1 btn" icon="add" @click="addNewQuestion">
      add question</va-button
    >
  </div>
</template>
<script>
export default {
  emits: ["addQuestion", "addAnswer"],
  data() {
    return {
      name: "",
      answer: "",
      questions: [],
      showCreateQuestion: false,
      showCreateAnswer: null,
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
      this.$emit("addQuestion", question);
      this.showCreateQuestion = false;
      this.name = "";
    },
    addAnswer(k) {
      console.log("addAnswer");
      this.$emit("addAnswer", k, this.answer);
      this.answer = "";
      this.showCreateAnswer = null;
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
.question_block {
  border-bottom: 1px solid #c4c4c4;
  padding: 10px 0;
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
.variants {
  font-size: 12px;
  color: #000;
  opacity: 0.4;
}
</style>
