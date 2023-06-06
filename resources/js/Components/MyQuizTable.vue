<template>
  <div>
    <Link :href="route('admin.create.quiz')">
      <va-button preset="plain" icon="add"> Add</va-button>
    </Link>
    <div class="table">
      <div class="header">
        <div v-for="head in headers" class="head">
          {{ head }}
        </div>

        <div class="head"></div>
      </div>

      <div v-for="(row, rowIndex) in data" class="row">
        <div v-for="(col, k) in row" class="column">
          <div v-if="k in headers" class="col">{{ col }}</div>
        </div>
        <div v-if="data[rowIndex]" class="column actions">
          <va-button
            @click="deleteQuiz(rowIndex, row['id'])"
            preset="plain"
            icon="delete"
            class="ml-3"
          />
          <va-button preset="plain" icon="edit" />
        </div>
      </div>
    </div>
  </div>

  <!-- <va-modal
    @cancel="addModalVisible = false"
    :model-value="addModalVisible"
    title="Create quizz"
    hide-default-actions
  >
    <QuizCreateForm @createQuiz="createQuiz" />

    <template #footer>
      <va-button @click="addModalVisible = false">Ok </va-button>
    </template>
  </va-modal> -->
</template>
<script>
import QuizCreateForm from "@/Components/QuizCreateForm.vue";
import NavLink from "@/Components/NavLink.vue";
import { Head, Link, router } from "@inertiajs/vue3";

export default {
  emits: ["deleteQuiz"],
  components: {
    QuizCreateForm,
    Link,
  },
  props: {
    quizzez: {
      type: Array,
      default: true,
    },
    headers: {
      type: Object,
      default: true,
    },
  },
  data() {
    return {
      data: this.quizzez,
    };
  },
  methods: {
    createQuiz(quiz) {
      let data = {
        id: quiz.id,
        name: quiz.name,
      };
      this.data.push(data);
    },
    deleteQuiz(index, id) {
      this.$emit("deleteQuiz", id);
      delete this.data[index];
    },
  },
};
</script>
<style scoped>
.table {
  width: 100%;
}

.header {
  border-bottom: 2px solid #dbcdcdc0;
  font-weight: 700;
}

.header,
.row {
  display: grid;
  grid-template-columns: 10% auto 10%;
}

.head,
.col {
  display: flex;
  justify-content: center;
  padding: 5px;
}
.actions {
  padding: 5px;
}
</style>
