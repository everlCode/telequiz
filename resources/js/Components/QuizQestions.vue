<template>
  <div class="questions">
    <div class="question_block" v-for="(q, k) in questions">
      <div class="question">
        <div class="name">#{{ k + 1 }} {{ q.name }}</div>
        <va-button
          v-if="showCreateVariant === null"
          class="btn"
          icon="add"
          @click="showCreateVariant = k; showCreateQuestion = false;"
        ></va-button>
      </div>
      <span v-if="q.variants.length > 0" class="variants">Variants:</span>
      <div class="variants ml-10">
        <div v-for="variant in q.variants" class="variant">
          {{ variant.name }}
        </div>
      </div>
      <div v-if="showCreateVariant === k" class="createVarinat flex items-center">
        <label class="mr-5" for="variant">Variant name:</label>
        <input v-on:keyup.enter="addVariant(k, q.id)" type="text" id="variant" v-model="variant" placeholder="variant" />
        <va-button v-if="variant" class="btn ml-4" icon="add" @click="addVariant(k, q.id)">
          Add variant
        </va-button>
      </div>
    </div>
  </div>

  <div v-if="showCreateQuestion" class="createQuestion mt-5">
    <label for="question">Question name:</label>
    <input v-on:keyup.enter="addQuestion(k)" v-model="name" id="question" type="text" placeholder="question" />
    <va-button class="my-1 btn mt-4" icon="add" @click="addQuestion(k)">
      create
    </va-button>
  </div>

  <div v-if="!showCreateQuestion" class="title mt-10">
    <va-button class="my-1 btn" icon="add" @click="addNewQuestion">
      add question</va-button
    >
  </div>
</template>
<script>

export default {
  emits: ["addQuestion", "addVariant"],
  props: {
    questions: {
      type: Array
    }
  },
  data() {
    return {
      name: "",
      variant: "",
      showCreateQuestion: false,
      showCreateVariant: null,
    };
  },
  methods: {
    addNewQuestion() {
      this.showCreateQuestion = true;
      this.showCreateVariant = null;
    },
    addQuestion(k) {
      if(!this.name) {
        alert('Fill the quiz name!');
        return;
      }

      const question = {
        name: this.name,
        variants: [],
      };

      //this.questions.push(question);
      this.$emit("addQuestion", question);
      this.showCreateQuestion = false;
      this.name = "";
    },
    addVariant(k, id) {
      console.log([k,id]);
      let variant = {
        name: this.variant,
        question_id: id,
      };
      this.$emit("addVariant", k, variant);
      this.variant = "";
      this.showCreateVariant = null;
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
.variant {
  color: #726f6f;
}
.createAnswer {
  display: flex;
  align-items: center;
  gap: 10px;
}
.variants {
  display: grid;
  grid-template-columns: 25% 25% 25% 25%;
}
</style>
