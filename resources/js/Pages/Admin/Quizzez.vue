<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quizzez</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <DataTable :value="quizzez" tableStyle="min-width: 50rem">
                    <Column field="id" header="Id"></Column>
                    <Column field="name" header="Name"></Column>
                    <Column field="remove" header="Remove" style="width: 5%">
                        <template #body="slotProps">
                            <Button icon="pi pi-times" severity="danger" aria-label="Cancel"
                                @click="removeQuiz(slotProps.data.id)" />
                        </template>
                    </Column>

                </DataTable>

                <Button label="Create" icon="pi pi-external-link" @click="visible = true" />

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
    setup() {
        const form = useForm({
            name: null
        })

        return { form }
    },
    data() {
        return {
            visible: false
        }
    },
    methods: {
        removeQuiz(id) {

            axios.delete('/quizzez/').then((response) => this.data = response.data.response)
                .catch((error) => console.log(error.response.data));
        }
    },
});
</script>