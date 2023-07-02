<template>
  <div class=" m-auto item-page">
    <div>
      <div class="section-title pb-4">
        <h2>{{ titleByFilter }}</h2>

        <button @click="toggleModal" class="btn btn-primary hover-btn">
          New Item
        </button>
      </div>
      <div class="item-listing">
        <div class="listing-header">
          <div class="checkbox-container col-md-2">
            <input type="checkbox" id="select-all" :checked="isCheckedAll" @click="toggleCheckAll"/>All
          </div>
          <div class="col-md-5">Name</div>
          <div class="col-md-4">Folder</div>
          <div class="col-md-1">
            <div class="three-dot-dropdown">
              <button type="button" class="three-dot-icon dropdown-toggle" data-bs-toggle="dropdown"
                      aria-expanded="false">
                      <div class="fa fa-ellipsis-v"></div>
              </button>
              <ul class="dropdown-menu">
                <div>
                  <li class="dropdown-item" @click="toggleMoveItemModal">
                    <i class="fa fa-exchange"></i>
                    Move Selected
                  </li>
                  <li class="dropdown-item" @click="deleteCheckedItems">
                    <i class="fa fa-trash"></i>
                    Delete Selected
                  </li>
                </div>
              </ul>
            </div>
          </div>
        </div>
        <div v-if="filteredItems.length === 0" class="col-md-12 text-center fw-bold p-2">
          <h3>There are no items to show</h3>
        </div>
        <div v-for="item in filteredItems" :key="item.id" class="listing-item">
          <div class="checkbox-container col-md-2">
            <input type="checkbox" :id="`item-${item.id}`" :checked="checkedItems.includes(item.id)"
                   @click="toggleCheckedItem(item.id)"
            />
            <label :for="`item-${item.id}`"></label>
          </div>
          <div class="col-md-5 d-flex">
            <div class="item-name">
              <p class="my-0 list-item-name" @click="handleEditItem( item )">{{ item.name }}</p>
            </div>
          </div>
          <div class="col-md-4">
            <p class="list-org-name" title="Folder">
              {{ getFolderName(item.folder_id) }}
            </p>
          </div>
          <div class="col-md-1">
            <div class="three-dot-dropdown">
              <button type="button" class="three-dot-icon dropdown-toggle" data-bs-toggle="dropdown"
                      aria-expanded="false">
                      <div class="fa fa-ellipsis-v"></div>
              </button>
              <ul class="dropdown-menu">
                <div>
                  <li class="dropdown-item" @click="copyToClipboard( 'Username', item.username)">
                    <i class="fa fa-user"></i>
                    Copy username
                  </li>
                  <li class="dropdown-item" @click="copyToClipboard( 'Password', item.password)">
                    <i class="fa fa-key"></i>
                    Copy password
                  </li>
                  <a v-if="getFirstUrl(item.urls)" :href="getFirstUrl(item.urls)" class="dropdown-item" target="_blank">
                    <i class="fa fa-external-link-square"></i>
                    Open Link
                  </a>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li class="dropdown-item" @click="deleteItem(item.id)">
                    <i class="fa fa-trash"></i>
                    Delete Item
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
    <moveItemModal
      :open-move-item-modal="openMoveItemModal"
      :folder-data="folderData"
      :checked-items="checkedItems"
      @move-checked-items="moveCheckedItems"
      @toggle-move-item-modal="toggleMoveItemModal"
    />
    <div v-if="openModal" class="modal-backdrop fade show"></div>
    <div v-if="openMoveItemModal" class="modal-backdrop fade show"></div>
  </div>
</template>

<script>
import Vue from 'vue';
import Toasted from 'vue-toasted';
import itemModal from '../modals/itemModal.vue';
import moveItemModal from '../modals/moveItemModal.vue';

export default {
  name: 'ItemList',
  components: {
    itemModal,
    moveItemModal
  },
  props: {
    selectMenu: Object,
    folderData: Array
  },
  data() {
    return {
      openModal: false,
      isEditModal: false,
      openMoveItemModal: false,
      itemData: [],
      checkedItems: [],
      isCheckedAll: false,
      selectedItemData: {},
    }
  },
  computed: {
    filteredItems(){
      if(this.selectMenu.menuType === 'folder'){
        return this.itemData.filter(item => (
          item.folder_id === this.selectMenu.typeValue
        ));
      }
      else if(this.selectMenu.menuType === 'favorite'){
        return this.itemData.filter(item => (
          item.favorite === '1'
        ));
      }
      return this.itemData;
    },
    titleByFilter(){
      if(this.selectMenu.menuType === 'folder'){
        return 'Items By Folder'
      }
      else if(this.selectMenu.menuType === 'favorite'){
        return 'Favorite Items';
      }
      return 'All Items';
    },
    getFolderName() {
      return function (folderId) {
        const folder = this.folderData.find((folder) => folder.id === folderId);
        return folder ? folder.foldername : '---';
      };
    },
    getFirstUrl() {
      return function (urls) {
        const url = JSON.parse(urls);
        return url[0] ? url[0] : '';
      };
    },
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

    // Checkbox Handling
    toggleCheckAll(){
      if(this.isCheckedAll){
        this.isCheckedAll = false;
        this.checkedItems = [];
      }
      else{
        this.isCheckedAll = true;
        this.checkedItems = this.filteredItems.map(item => item.id);
      }
    },
    toggleCheckedItem( selectedID ){
      if(this.checkedItems.includes(selectedID)){
        this.checkedItems = this.checkedItems.filter((id) => id !== selectedID);
        this.isCheckedAll = false;
      }
      else{
        this.checkedItems = [...this.checkedItems, selectedID];
        if(this.checkedItems.length === this.filteredItems.length){
          this.isCheckedAll = true;
        }
      }
    },
    toggleMoveItemModal(){
      this.openMoveItemModal = !this.openMoveItemModal;
    },

    // Copy To Clipboard
    copyToClipboard ( name, text ) {
      this.showToast(`${name} copied`, 'success');
      navigator.clipboard.writeText(text);
    },

    // Toaster
    showToast(message, type) {
      this.$toasted.show(message, {
        theme: "toasted-primary",
        position: "top-center",
        duration: 3000,
        type: type,
      });
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
        this.showToast('Item Updated Successfully', 'success');
        this.itemData = this.itemData.map(item => {
          if (item.id === data.id) {
            return {...item, ...data.values};
          }
          return item;
        });
      } else {
        this.showToast('Item Added Successfully', 'success');
        this.getItems();
      }
    },
    deleteItemData(id) {
      this.showToast('Item Deleted Successfully', 'success');
      this.itemData = this.itemData.filter((item) => item.id !== id);
    },

    /***** AJAX CALL *****/
    getItems() {
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const itemAction = 'item_endpoints';
      window.jQuery.ajax({
        url: ajaxUrl,
        data: {
          action: itemAction,
          route: 'get_items',
          nonce: nonce
        }
      })
      .then( response => {
          this.itemData = response.data;
      })
      .fail( error => {
        this.showToast(error.responseJSON.data.message, 'error');
      });
    },
    addOrUpdateItem( params ) {
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const itemAction = 'item_endpoints';

      const dataToSubmit = {
        action: itemAction,
        route: 'create_or_update_item',
        ...params,
        nonce: nonce,
      };

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST'
      })
      .then( response => {
          this.updateItemData(response.data);
          console.log(response.data);
      })
      .fail( error => {
        this.showToast(error.responseJSON.data.message, 'error');
      });
    },
    deleteItem(id) {
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const itemAction = 'item_endpoints';

      const dataToSubmit = {
        action: itemAction,
        route: 'delete_item',
        id: id,
        nonce: nonce,
      }

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST'
      })
      .then( response => {
        this.deleteItemData(response.data.id);
      })
      .fail( error => {
        this.showToast(error.responseJSON.data.message, 'error');
      });
    },

    // API Call for Selected Items
    moveCheckedItems( folderID ){
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const itemAction = 'item_endpoints';

      // Converting to Json without Key
      let selectedItems = JSON.stringify(this.checkedItems);
      selectedItems = JSON.parse(selectedItems);

      const dataToSubmit = {
        action: itemAction,
        route: 'move_items',
        ids: selectedItems,
        folder_id: folderID,
        nonce: nonce,
      };

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST',
      })
      .then( () => {
          this.showToast('Items Moved Successfully', 'success');
          this.getItems();
          this.checkedItems = [];  
          this.isCheckedAll = false;      
      })
      .fail( error => {
          this.showToast(error.responseJSON.data.message, 'error');
      });
    },
    deleteCheckedItems(){
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const itemAction = 'item_endpoints';

      // Converting to Json without Key
      let selectedItems = JSON.stringify(this.checkedItems);
      selectedItems = JSON.parse(selectedItems);

      const dataToSubmit = {
        action: itemAction,
        route: 'delete_items',
        ids: selectedItems,
        nonce: nonce,
      };

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST',
      })
      .then(() => {
          this.showToast('Items Deleted Successfully', 'success');
          this.getItems();
          this.checkedItems = [];
          this.isCheckedAll = false;
      })
      .fail( error => {
          this.showToast(error.responseJSON.data.message, 'error');
      });
    }
  },
  created() {
    Vue.use(Toasted);
  },
  mounted() {
    this.getItems();
  }
}
</script>

<style>
@import '../../assets/styles/item.scss';
</style>