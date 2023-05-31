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

  <va-modal class="modal-crud-example" :model-value="isCreateModalVisible" title="Create item" size="small"
    hide-default-actions @cancel="closeModalToCreateItem">
    <va-form tag="form" class="mb-2 flex flex-col gap-2" @submit.prevent="addNewItem">
      <va-input v-for="key in Object.keys(editFields)" :key="key" v-model='createItem[key]' class="my-3" :label="key" />
      <va-button type="submit" class="mt-3">
        Create
      </va-button>
    </va-form>
  </va-modal>
</template>
<script>
import { defineComponent } from "vue";
import axios from 'axios';

export default defineComponent({
  props: {
    items: {
      type: Array,
      default: true,
    },
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
      createItem,
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
    resetCreatedItem() {
      this.createdItem = { ...defaultItem };
    },
    deleteItemById(id) {
      let rid = this.items[id]['id'];
      axios.delete(`/api/quiz/${rid}`).then(response => {
        location.reload()
      })
    },
    addNewItem() {
      axios.put('/api/quiz/', this.createItem).then(response => {
        this.items.push(this.createItem);
      }).finally(() => {
        this.closeModalToCreateItem();
      })
    },
    editItem() {
      let rowId = this.editedItemRowId;
      let id = this.items[rowId]['id'];
      axios.post(`/api/quiz/${id}`, this.editedItem).then(response => {
        console.log(response);
        this.items[rowId] = this.editedItem;
      }).finally(() => {
        this.resetEditedItem();
      })
    },
    openModalToCreateItem() {
      this.isCreateModalVisible = true;
    },
    closeModalToCreateItem() {
      this.isCreateModalVisible = null;
    },
    openModalToEditItemById(id) {
      this.editedItemRowId = id;
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