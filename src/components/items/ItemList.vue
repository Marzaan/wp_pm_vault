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
                    Move Selected
                  </li>
                  <li class="dropdown-item" @click="deleteCheckedItems">
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
            <input type="checkbox" id="item-1" :checked="checkedItems.includes(item.id)"
                   @click="toggleCheckedItem(item.id)"
            />
            <label for="item-1"></label>
          </div>
          <div class="col-md-5 d-flex">
            <div class="item-name">
              <p class="my-0 list-item-name" @click="handleEditItem( item )">{{ item.name }}</p>
            </div>
          </div>
          <div class="col-md-4">
            <p v-if="item.foldername" class="list-own-name" title="Folder">
              {{ item.foldername }}
            </p>
            <p v-else>
              --
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
                  <li class="dropdown-item" @click="copyToClipboard(item.username)">
                    Copy username
                  </li>
                  <li class="dropdown-item" @click="copyToClipboard(item.password)">
                    Copy password
                  </li>
                  <li class="dropdown-item" @click="deleteItem(item.id)">
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
    <moveItemModal
      :open-move-item-modal="openMoveItemModal"
      :folder-data="folderData"
      :checked-items="checkedItems"
      @move-checked-items="moveCheckedItems"
      @toggle-move-item-modal="toggleMoveItemModal"
    />
  </div>
</template>

<script>
import itemModal from '../../modals/itemModal.vue';
import moveItemModal from '../../modals/moveItemModal.vue';

export default {
  name: 'ItemList',
  components: {
    itemModal,
    moveItemModal
  },
  props: {
    selectMenu: Object
  },
  data() {
    return {
      openModal: false,
      isEditModal: false,
      openMoveItemModal: false,
      itemData: [],
      folderData: [],
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
    copyToClipboard ( text ) {
        navigator.clipboard.writeText(text);
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
      const folderAction = 'folder_endpoints'
      window.jQuery.ajax({
        url: ajaxUrl,
        data: {
          action: folderAction,
          route: 'get_folders'
        },
        success: (response) => {
          this.folderData = response.data;
        }
      });
    },
    getItems() {
      const ajaxUrl = window.ajax_object.ajax_url;
      const itemAction = 'item_endpoints';
      window.jQuery.ajax({
        url: ajaxUrl,
        data: {
          action: itemAction,
          route: 'get_items',
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
      const itemAction = 'item_endpoints';

      const dataToSubmit = {
        action: itemAction,
        route: 'create_or_update_item',
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
      const itemAction = 'item_endpoints';

      const dataToSubmit = {
        action: itemAction,
        route: 'delete_item',
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
      console.log("dataToSubmit", dataToSubmit);

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST',
        success: (response) => {
          if (response.success) {
            this.getItems();
            this.checkedItems = [];
          } else {
            console.log("Server Error", response);
          }
        }
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
      console.log("dataToSubmit", dataToSubmit);

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST',
        success: (response) => {
          if (response.success) {
            this.getItems();
            this.checkedItems = [];
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