<template>
  <div :class="['modal fade', openModal ? 'show' : '']" :style="{ 'display': openModal ? 'block' : 'none' }">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ modalTitle }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                  @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 mb-4">
              <label class="form-label fw-bold">Folder Name</label>
              <input v-if="!updatingFolder" type="text" class="form-control" placeholder="Name"
                     required
                     v-model="foldername"
              />
              <input v-else type="text" class="form-control" placeholder="Name"
                     required
                     v-model="selectedFolderName"
              />
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light justify-content-between">
          <div>
            <button v-if="!updatingFolder" type="button" class="btn btn-dark" @click="handleFolder()">Save</button>
            <button v-else type="button" class="btn btn-success"
                    @click="handleFolder( selectedFolderID, selectedFolderName  )">Update
            </button>
            <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
          </div>
          <div>
            <button v-if="updatingFolder" type="button" class="btn btn-danger" @click="handleDeleteFolder">Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "folderModal",
  props: {
    openModal: Boolean,
    isEditModal: Boolean,
    selectedFolderData: Object,
    toggleModal: Function,
  },
  emits: [
    'toggle-modal',
    'add-or-update-folder',
    'delete-folder'
  ],
  data() {
    return {
      foldername: '',
      selectedFolderID: '',
      selectedFolderName: '',
      updatingFolder: false,
    };
  },
  watch: {
    isEditModal() {
      if (this.isEditModal) {
        this.updatingFolder = true;
        this.selectedFolderID = this.selectedFolderData.id;
        this.selectedFolderName = this.selectedFolderData.foldername;
      } else {
        this.foldername = '';
        this.updatingFolder = false;
      }
    }
  },
  computed: {
    modalTitle() {
      return this.updatingFolder ? 'Update Folder' : 'Add Folder';
    }
  },
  methods: {
    closeModal() {
      this.$emit('toggle-modal');
    },
    handleFolder(id = null, foldername = this.foldername) {
      const params = {
        'id': id,
        'foldername': foldername
      };
      this.foldername = '';
      this.closeModal();
      this.$emit('add-or-update-folder', params);
    },
    handleDeleteFolder() {
      this.closeModal();
      this.$emit('delete-folder', this.selectedFolderData.id);
    }
  }
};
</script>