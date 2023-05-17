<!-- <template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quizzez</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <DataTable :value="title" tableStyle="min-width: 50rem; margin-bottom: 10px">
                    <Column field="id" header="Id"></Column>
                    <Column field="name" header="Name"></Column>
                    <Column field="remove" header="Remove" style="width: 5%">
                        <template #body="slotProps">
                            <Button icon="pi pi-times" severity="danger" aria-label="Cancel"
                                @click="removeQuiz(slotProps.index, slotProps.data.id)" />
                        </template>
                    </Column>

                </DataTable>

                <Button label="Create" icon="pi" @click="visible = true" />
                <Dialog v-model:visible="visible" modal header="Header" :style="{ width: '50vw' }">
                    <template #header>
                        Create quiz
                    </template>
                    <form @submit.prevent="form.post(route('admin.quizzez.store'))" class="flex flex-col">
                        <label for="name" class="block text-sm font-medium text-gray-700"> Name </label>
                        <input type="text" name="name" id="name" v-model="form.name" class="mb-5">
                        <button type="submit" :disabled="form.processing" @click="visible = false"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                    </form>
                </Dialog>


            </div>
        </div>
    </app-layout>
</template>

<style>
body {
    font-family: var(--font-family);
}
</style>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import axios from 'axios';
import QuizTable from '@/Components/quizTable.vue';
import { ref, toRef } from 'vue';

export default defineComponent({
    components: {
        AppLayout,
        Button,
        Dialog,
        DataTable,
        Column
    },
    props: {
        quizzez: Array,
    },
    setup(props) {
        const form = useForm({
            name: null
        })
    

    // OR, turn a single property on `props` into a ref
    const title = ref()

    title.value = props.quizzez

    return { title, form }
    },
    data() {
        return {
            visible: false,
            quizzezData: ref(this.quizzez)
        }
    },
    methods: {
        removeQuiz(i, id) {
       
            var url = '/api/quiz/' + id;
            axios.delete(url).then((response) => console.log(response.data.response))
                .catch((error) => console.log(error.response.data));

            delete title[i];
            //location.reload();
        }
    },
});



</script> -->




<template>
    <div class="card">
        <DataTable :value="quizzez" stripedRows tableStyle="min-width: 50rem">
            <Column field="code" header="Code"></Column>
            <Column field="name" header="Name"></Column>
            <Column field="category" header="Category"></Column>
            <Column field="quantity" header="Quantity">
                <template #body="slotProps">
                            <Button icon="pi pi-times" severity="danger" aria-label="Cancel"
                                @click="update()" />
                        </template></Column>
        </DataTable>
    </div>
</template>

<script setup>

import { ref, onMounted } from 'vue';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Button from 'primevue/button';


onMounted((props) => {
    console.log('mount')
});

function update() {
    quizzez.value.pop();
}
console.log('ref')
const quizzez = ref();
const props =  defineProps({
      quizzez: Array,
   })
quizzez.value = props.quizzez   

console.log(props.quizzez)

</script>
