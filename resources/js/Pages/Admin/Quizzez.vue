<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quizzez</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-200 mb-10">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                            </th>
                            <th scope="col"
                                class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="quiz in quizzez">
                            <td class="px-2 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ quiz.id }}
                                </div>
                            </td>

                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        {{ quiz.name }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Button label="Create" icon="pi pi-external-link" @click="visible = true" />

                <Dialog v-model:visible="visible" modal header="Header" :style="{ width: '50vw' }">
                    <template #header>
                        Create quiz
                    </template>
                    <form @submit.prevent="form.post(route('admin.quizzez.store'))">
                            <input type="text" name="name" id="name" v-model="form.name">
                            <button type="submit" :disabled="form.processing" @click="visible = false"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                        </form> 
                </Dialog>

                
            </div>
        </div>
    </app-layout>
</template>

<style></style>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';



export default defineComponent({
    components: {
        AppLayout,
        Button,
        Dialog
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
    }
});
</script>