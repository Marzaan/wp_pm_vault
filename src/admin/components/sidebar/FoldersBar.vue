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
                <i class="fa fa-folder p-1" aria-hidden="true"></i>  {{ data.foldername.length > 15 ? data.foldername.slice(0, 15) + ".." : data.foldername }}
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
import folderModal from '../modals/folderModal.vue';

export default {
  name: "FoldersBar",
  components: {
    folderModal,
  },
  props: {
    selectMenu: Object,
    folderData: Array
  },
  emits: [
    'set-select-menu',
    'get-folders',
    'update-folder-data'
  ],
  data() {
    return {
      openModal: false,
      isEditModal: false,
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
        this.$showToast('Folder Updated Successfully', 'success');
        const updatedData = this.folderData.map(folder => {
          if (folder.id === data.id) {
            return {...folder, foldername: data.name};
          }
          return folder;
        });
        this.$emit('update-folder-data', updatedData);
      } else {
        this.$showToast('Folder Added Successfully', 'success');
        this.$emit('get-folders');
      }
    },
    deleteFolderData(id) {
      this.$showToast('Folder Deleted Successfully', 'success');
      const updatedData = this.folderData.filter(folder => folder.id !== id);
      this.$emit('update-folder-data', updatedData);
    },

    /********* AJAX Call *********/
    addOrUpdateFolder(params) {
      const dataToSubmit = {
        action: 'folder_endpoints',
        route: 'create_or_update_folder',
        id: params.id,
        name: params.foldername,
        nonce: this.$nonce,
      }

      window.jQuery.ajax({
        url: this.$ajaxUrl,
        data: dataToSubmit,
        method: 'POST'
      })
      .then( response => {
        this.updateFolderData(response.data);
      })
      .fail( error => {
        this.$showToast(error.responseJSON.data.message, 'error');
      });
    },
    deleteFolder(id) {
      const dataToSubmit = {
        action: 'folder_endpoints',
        route: 'delete_folder',
        id: id,
        nonce: this.$nonce,
      }

      window.jQuery.ajax({
        url: this.$ajaxUrl,
        data: dataToSubmit,
        method: 'POST'
      })
      .then( response => {
        this.deleteFolderData(response.data.id);
      })
      .fail( error => {
        this.$showToast(error.responseJSON.data.message, 'error');
      });
    }
  }
};
</script>
