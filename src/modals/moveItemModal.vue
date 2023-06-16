<template>
    <div :class="['modal fade', openMoveItemModal ? 'show' : '']" :style="{ 'display': openMoveItemModal ? 'block' : 'none' }">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Move To Folder</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 mb-4">
                <label class="form-label fw-bold">Select Folder</label>
                <select class="form-select" v-model="folderID">
                  <option :value="null">-- Select --</option>
                  <option v-for="folder in folderData" :key="folder.id" :value="folder.id">{{ folder.foldername }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light justify-content-start">
              <button type="button" class="btn btn-success" @click="handleMoveItems()">Move</button>
              <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "moveItemModal",
    props: {
      openMoveItemModal: Boolean,
      folderData: Array,
      checkedItems: Array,
      moveCheckedItems: Function,
      toggleMoveItemModal: Function,
    },
    emits: [
      'toggle-move-item-modal',
      'move-checked-items',
    ],
    data() {
      return {
        folderID: null,
      };
    },
    methods: {
      closeModal() {
        this.$emit('toggle-move-item-modal');
      },
      handleMoveItems() {
        this.closeModal();
        this.$emit('move-checked-items', this.folderID);
        this.folderID = null;
      }
    }
  };
  </script>