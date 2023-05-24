<template>
  <va-button preset="plain" icon="add" @click="openModalToCreateItem(rowIndex)" />
  <va-data-table class="table-crud-example" :items="items" :columns="columns" striped>



    <template #cell(actions)="{ rowIndex, rowData }">
      <va-button preset="plain" icon="edit" @click="openModalToEditItemById(rowIndex)" />
      <va-button preset="plain" icon="delete" @click="deleteItemById(rowIndex)" />
    </template>

  </va-data-table>

  <va-modal class="modal-crud-example" :model-value="!!editedItem" title="Edit item" size="small" @ok="editItem"
    @cancel="resetEditedItem">
    <va-input v-for="key in Object.keys(editedItem)" :key="key" v-model="editedItem[key]" class="my-3" :label="key" />
  </va-modal>

  <va-modal class="modal-crud-example" :model-value="!!editedItem" title="Edit item" size="small" @ok="editItem"
    @cancel="resetEditedItem">
    <va-input v-for="key in Object.keys(editedItem)" :key="key" v-model="editedItem[key]" class="my-3" :label="key" />
  </va-modal>

  <va-modal class="modal-crud-example" :model-value="isCreateModalVisible" title="Create item" size="small" hide-default-actions>
    <va-form ref="myForm" stateful class="mb-2 flex flex-col gap-2" @submit.prevent="addNewItem">
      <va-input v-for="key in Object.keys(editFields)" :key="key" v-model='createItem[key]' class="my-3" :label="key" />
      <va-button type="submit" class="mt-3">
        Create
      </va-button>
    </va-form>
  </va-modal>
</template>
<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    items: {
      type: Array,
    }
  },
  data() {
    const columns = [
      { key: "name", sortable: true },
      { key: "actions", width: 80 },
    ];

    const editFields = {
      name: 'name'
    };

    const createItem = {
      name: '',
    }

    return {
      columns,
      editedItemId: null,
      editedItem: null,
      editFields,
      isCreateModalVisible: null,
      createItem
    };
  },

  computed: {
    isNewData() {
      return Object.keys(this.createdItem).every(
        (key) => !!this.createdItem[key]
      );
    },
  },

  methods: {
    resetEditedItem() {
      this.editedItem = null;
      this.editedItemId = null;
    },
    resetEditedItem() {
      this.isCreateModalVisible = null;
    },
    resetCreatedItem() {
      this.createdItem = { ...defaultItem };
    },
    deleteItemById(id) {
      this.items = [...this.items.slice(0, id), ...this.items.slice(id + 1)];
    },
    addNewItem() {
      console.log(this.createItem)
      console.log(2323)
      // this.items = [...this.items, { ...this.createdItem }];
      // this.resetCreatedItem();
    },
    editItem() {
      this.items[this.editedItemId] = this.editedItem;
      this.resetEditedItem();
    },
    openModalToCreateItem() {
      this.isCreateModalVisible = true;
    },
    openModalToEditItemById(id) {
      this.editedItemId = id;
      this.editedItem = { ...this.items[id] };

      for (const key in this.editedItem) {
        if (!this.editFields.hasOwnProperty(key)) {
          delete this.editedItem[key];
        }
      }
    },
  },
});
</script>