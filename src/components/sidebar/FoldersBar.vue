<template>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
      <button class="accordion-button" type="button" data-bs-toggle="collapse"
              data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true"
              aria-controls="panelsStayOpen-collapseThree">
        Folders
      </button>
    </h2>
    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show"
         aria-labelledby="panelsStayOpen-headingThree">
      <div class="accordion-body">
        <ul class="list-group list-group-flush">
          <template>
            <li v-for="data in folderData" :key="data.id"
                class="list-group-item d-flex justify-content-between align-items-center folder-list">
              <div :class="['side-menu-item', selectMenu.typeValue === data.id ? 'active-menu' : '']"
                  @click="handleSelectMenu( data.id )"
              >
                <i class="fa fa-folder" aria-hidden="true"></i>  {{ data.foldername.length > 15 ? data.foldername.slice(0, 15) + ".." : data.foldername }}
              </div>
              <span>
                <i class="fa fa-ellipsis-v" @click="handleEditFolder(data.id, data.foldername)"></i>
              </span>
            </li>
          </template>
          <li class="list-group-item side-menu-item" @click="toggleModal"><i class="fa fa-plus"></i> Add Folder</li>
        </ul>
      </div>
    </div>
    <folder-modal
        :open-modal="openModal"
        :is-edit-modal="isEditModal"
        :selected-folder-data="selectedFolderData"
        @add-or-update-folder="addOrUpdateFolder"
        @delete-folder="deleteFolder"
        @toggle-modal="toggleModal"
    />
    <div v-if="openModal" class="modal-backdrop fade show"></div>
  </div>
</template>

<script>
import Vue from 'vue';
import Toasted from 'vue-toasted';
import folderModal from '../modals/folderModal.vue';

export default {
  name: "FoldersBar",
  components: {
    folderModal,
  },
  props: {
    selectMenu: Object
  },
  emits: [
    'set-select-menu'
  ],
  data() {
    return {
      openModal: false,
      isEditModal: false,
      folderData: [],
      selectedFolderData: {},
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

    // Toaster
    showToast(message, type) {
      this.$toasted.show(message, {
        theme: "toasted-primary",
        position: "top-center",
        duration: 3000,
        type: type,
      });
    },

    // Select Folder Method for Filtering Item List
    handleSelectMenu( value ) {
      this.$emit('set-select-menu', 'folder', value);
    },

    // Edit, Update, Delete Folder
    handleEditFolder(id, foldername) {
      this.selectedFolderData = {
        id: id,
        foldername: foldername,
      },
      this.toggleModal();
      this.toggleEditModal();
    },
    updateFolderData(data) {
      if (data.updated) {
        this.showToast('Folder Updated Successfully', 'success');
        this.folderData = this.folderData.map(folder => {
          if (folder.id === data.id) {
            return {...folder, foldername: data.name};
          }
          return folder;
        });
      } else {
        this.showToast('Folder Added Successfully', 'success');
        this.getFolders();
      }
    },
    deleteFolderData(id) {
      this.showToast('Folder Deleted Successfully', 'success');
      this.folderData = this.folderData.filter(folder => folder.id !== id);
    },

    /********* AJAX Call *********/
    getFolders() {
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const folderAction = 'folder_endpoints';
      window.jQuery.ajax({
        url: ajaxUrl,
        data: {
          action: folderAction,
          route: 'get_folders',
          nonce: nonce
        },
        method: 'GET',
      })
      .then( response => {
          this.folderData = response.data;
          console.log(this.folderData);
      })
      .fail( error => {
        this.showToast(error.responseJSON.data.message, 'error');
        console.log("failed", error.responseJSON.data.message);
      });
    },
    addOrUpdateFolder(params) {
      const ajaxUrl = window.ajax_object.ajax_url;
      const folderAction = 'folder_endpoints';
      const nonce = window.ajax_object.nonce;

      const dataToSubmit = {
        action: folderAction,
        route: 'create_or_update_folder',
        id: params.id,
        name: params.foldername,
        nonce: nonce,
      }

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST'
      })
      .then( response => {
        console.log("response", response);
        this.updateFolderData(response.data);
      })
      .fail( error => {
        this.showToast(error.responseJSON.data.message, 'error');
        console.log("failed", error.responseJSON.data.message);
      });
    },
    deleteFolder(id) {
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const folderAction = 'folder_endpoints';

      const dataToSubmit = {
        action: folderAction,
        route: 'delete_folder',
        id: id,
        nonce: nonce,
      }

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST'
      })
      .then( response => {
        console.log("response", response);
        this.deleteFolderData(response.data.id);
      })
      .fail( error => {
        this.showToast(error.responseJSON.data.message, 'error');
        console.log("failed", error.responseJSON.data.message);
      });
    }
  },
  created() {
    Vue.use(Toasted);
  },
  mounted() {
    this.getFolders();
  }
};
</script>
