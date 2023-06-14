<template>
  <div class=" m-auto item-page">
    <div>
      <div class="section-title pb-4">
        <h2>All Items</h2>

        <button @click="toggleModal" class="btn btn-primary hover-btn">
          New Item
        </button>
      </div>
      <div class="item-listing">
        <div class="listing-header">
          <div class="checkbox-container col-md-2">
            <input type="checkbox" id="select-all" @click=""/>All
          </div>
          <div class="col-md-6">Name</div>
          <div class="col-md-3">Folder</div>
          <div class="col-md-1">
            <div class="three-dot-dropdown">
              <button type="button" class="three-dot-icon dropdown-toggle" data-bs-toggle="dropdown"
                      aria-expanded="false">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
              </button>
              <ul class="dropdown-menu">
                <div>
                  <li class="dropdown-item" @click="">
                    Permanently Delete Selected
                  </li>
                </div>
                <div>
                  <li class="dropdown-item" @click="">
                    Move Selected
                  </li>
                  <li class="dropdown-item" @click="">
                    Move Selected To Org..
                  </li>
                  <li class="dropdown-item" @click="">
                    Delete Selected
                  </li>
                </div>
              </ul>
            </div>
          </div>
        </div>
        <div v-for="item in itemData" :key="item.id" class="listing-item">
          <div class="checkbox-container col-md-2">
            <input type="checkbox" id="item-1"
                   @change=""
            />
            <label htmlFor="item-1"></label>
          </div>
          <div class="col-md-6 d-flex">
            <div class="item-name">
              <p class="my-0 list-item-name" @click="handleEditItem( item )">{{ item.name }}</p>
            </div>
          </div>
          <div class="col-md-3">
            <p v-if="item.foldername" class="list-own-name" title="Folder">
              {{ item.foldername }}
            </p>
          </div>
          <div class="col-md-1">
            <div class="three-dot-dropdown">
              <button type="button" class="three-dot-icon dropdown-toggle" data-bs-toggle="dropdown"
                      aria-expanded="false">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
              </button>
              <ul class="dropdown-menu">
                <div>
                  <li class="dropdown-item" @click="">
                    Restore Item
                  </li>
                  <li class="dropdown-item" @click="">
                    Permanently Delete
                  </li>
                </div>
                <div>
                  <li class="dropdown-item" @click="">
                    Delete Selected
                  </li>
                </div>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <itemModal
      :open-modal="openModal"
      :is-edit-modal="isEditModal"
      :folder-data="folderData"
      :selected-item-data="selectedItemData"
      @add-or-update-item="addOrUpdateItem"
      @delete-item="deleteItem"
      @toggle-modal="toggleModal"
    />
  </div>
</template>

<script>
import itemModal from '../../modals/itemModal.vue';

export default {
  name: 'ItemList',
  components: {
    itemModal
  },
  data() {
    return {
      openModal: false,
      isEditModal: false,
      itemData: [],
      folderData: [],
      selectedItemData: {},
    }
  },
  methods: {
    // Modal
    toggleModal() {
      this.openModal = !this.openModal;
      this.isEditModal = false;
    },
    toggleEditModal() {
      this.isEditModal = true;
    },

    // Edit, Update, Delete Item
    handleEditItem( item ) {
      this.selectedItemData = {
        id: item.id,
        folderID: item.folder_id,
        name: item.name,
        username: item.username,
        password: item.password,
        urls: item.urls,
        notes: item.notes,
        favorite: item.favorite,
      },
      this.toggleModal();
      this.toggleEditModal();
    },
    updateItemData(data) {
      if (data.updated) {
        this.itemData = this.itemData.map(item => {
          if (item.id === data.id) {
            return {...item, ...data.values};
          }
          return item;
        });
      } else {
        this.getItems();
      }
    },
    deleteItemData(id) {
      this.itemData = this.itemData.filter((item) => item.id !== id);
    },

    /***** AJAX CALL *****/
    getFolders() {
      const ajaxUrl = window.ajax_object.ajax_url;
      window.jQuery.ajax({
        url: ajaxUrl,
        data: {
          action: 'get_folders',
        },
        success: (response) => {
          this.folderData = response.data;
        }
      });
    },
    getItems() {
      const ajaxUrl = window.ajax_object.ajax_url;
      window.jQuery.ajax({
        url: ajaxUrl,
        data: {
          action: 'get_items',
        },
        success: (response) => {
          console.log(response);
          if(response.success){
            this.itemData = response.data;
          }
          else{
            console.log("Server Error");
          }
        }
      });
    },
    addOrUpdateItem( params ) {
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;

      const dataToSubmit = {
        action: 'create_or_update_item',
        id: params.id,
        name: params.name,
        folderID: params.folderID,
        username: params.username,
        password: params.password,
        urls: params.urls,
        notes: params.notes,
        favorite: params.favorite,
        nonce: nonce,
      };
      console.log("dataToSubmit", dataToSubmit);

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST',
        success: (response) => {
          console.log(response);
          if (response.success) {
            this.updateItemData(response.data);
          } else {
            console.log("Server Error", response);
          }
        }
      });
    },
    deleteItem(id) {
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;

      const dataToSubmit = {
        action: 'delete_item',
        id: id,
        nonce: nonce,
      }
      console.log("dataToSubmit", dataToSubmit);

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST',
        success: (response) => {
          if (response.success) {
            console.log("response", response);
            this.deleteItemData(response.data.id);
          } else {
            console.log("Server Error", response);
          }
        }
      });
    }
  },
  mounted() {
    this.getItems();
    this.getFolders();
  }
}
</script>

<style>
@import '../../assets/styles/item.scss';
</style>