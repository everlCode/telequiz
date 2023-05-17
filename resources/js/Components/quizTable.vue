
<template>
    <div class="card">
        <DataTable :value="products" stripedRows tableStyle="min-width: 50rem">
            <Column field="code" header="Code"></Column>
            <Column field="name" header="Name"></Column>
            <Column field="category" header="Category"></Column>
            <Column field="quantity" header="Quantity"></Column>
        </DataTable>
    </div>
</template>

<script setup>

import { ref, onMounted } from 'vue';


onMounted(() => {
   products.value = {'code': 'fgfg', 'name': 'dffg', 'category': 'dfgfg', 'quantity': 'dffgfg'}
});

const products = ref();

</script>
