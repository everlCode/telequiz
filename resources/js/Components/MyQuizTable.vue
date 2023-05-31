<template>
    <div>
        <va-button @click="$emit('addQuiz')" preset="plain" icon="add"> Add</va-button>
        <div class="table">
            <div class="header">
                <div v-for="head in headers" class="head">
                    {{ head }}
                </div>

                <div class="head"></div>
            </div>

            <div v-for="row, rowIndex in data" class="row">
                <div v-for="col, k in row" class="column">
                    <div v-if="k in headers" class="col"> {{ col }}</div>
                </div>
                <div v-if="data[rowIndex]" class="column actions">
                    <va-button @click="deleteQuiz(rowIndex, row['id'])" preset="plain" icon="delete" class="ml-3" />
                    <va-button preset="plain" icon="edit" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        quizzez: {
            type: Array,
            default: true,

        },
        headers: {
            type: Object,
            default: true
        },
    },
    data() {
        return {
            data: this.quizzez
        }
    },
    methods: {
        deleteQuiz(index, id) {
            this.$emit('deleteQuiz', id)
            delete this.data[index];
        }   
    }
}
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