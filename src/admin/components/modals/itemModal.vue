<template>
    <div :class="['modal fade', openModal ? 'show' : '']" :style="{ display: openModal ? 'block' : 'none' }">
      <div class="modal-dialog modal-dialog-scrollable modal__width">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">{{ modalTitle }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-6 mb-4">
                <label class="form-label fw-bold">Name</label>
                <input type="text" class="form-control" placeholder="Name" required v-model="localSelectedItemData.name" />
              </div>
              <div class="col-6 mb-4">
                <label class="form-label fw-bold">Folder</label>
                <select class="form-select" v-model="folderID">
                  <option value="null">-- Select --</option>
                  <option v-for="folder in localFolderData" :key="folder.id" :value="folder.id">{{ folder.foldername }}</option>
                </select>
              </div>
                <div class="row">
                    <div class="col-6 mb-4">
                        <label class="form-label fw-bold">Username</label>
                        <div class="input-group mb-3">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Username"
                              v-model="localSelectedItemData.username"
                            />
                            <span class="input-group-text">
                                <i class="fa fa-clone cursor-pointer" aria-hidden="true" @click="copyToClipboard(localSelectedItemData.username)"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <label class="form-label fw-bold">Password</label>
                        <div class="input-group mb-3">
                            <input
                              class="form-control"
                              :type="passwordVisibility"
                              placeholder="Password"
                              v-model="localSelectedItemData.password"
                            />
                            <span class="input-group-text">
                              <i class="fa fa-clone cursor-pointer" @click="copyToClipboard(localSelectedItemData.password)"></i>
                            </span>
                            <span class="input-group-text">
                                <div class="cursor-pointer" @click="togglePasswordVisibility">
                                    <i class="fa fa-eye-slash"></i>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div v-for="(field, index) in loginUrls" :key="index" class="col-6 mb-4">
                        <label class="form-label fw-bold">URL {{ index + 1 }}</label>
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="https://google.com"
                                v-model=loginUrls[index]
                            />
                            <span class="input-group-text">
                              <i class="fa fa-clone cursor-pointer" @click="copyToClipboard(loginUrls[index])"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <a class="link-info cursor-pointer" @click="addNewField"> New URL</a>
                    </div>
                </div>
              <div class="col-12 mb-4">
                <label class="form-label fw-bold">Notes</label>
                <textarea class="form-control" rows="3" v-model="localSelectedItemData.notes"></textarea>
              </div>
              <div class="col-12 mb-4">
                <div class="row">
                  <div class="col-6">
                    <label for="favCheckbox" class="form-label fw-bold">Favorite</label>
                    <span class="mx-1"></span>
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="favCheckbox"
                      v-model="isFavorite"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light justify-content-between">
            <div>
                <button v-if="!updatingItem" type="button" class="btn btn-dark" @click="handleItem()">Save</button>
                <button v-else type="button" class="btn btn-success"
                    @click="handleItem( localSelectedItemData.id )">Update</button>
                <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
            </div>
            <div>
                <button v-if="updatingItem" type="button" class="btn btn-danger" @click="handleDeleteItem">Delete</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

<script>
import Vue from 'vue';
import Toasted from 'vue-toasted';
export default {
    name: "itemModal",
    props: {
        openModal: Boolean,
        isEditModal: Boolean,
        folderData: Array,
        selectedItemData: Object,
        toggleModal: Function,
    },
    emits: [
        'toggle-modal',
        'add-or-update-item',
        'delete-item'
    ],
    data() {
        return {
          loginUrls: [''],
          folderID: null,
          isFavorite: false, 
          localFolderData: [],
          localSelectedItemData: {},
          isPasswordVisible: false,
          updatingItem: false,
        }
    },
    watch: {
        isEditModal() {
            if (this.isEditModal) {
                let urls = this.selectedItemData.urls;
                let favorite = this.selectedItemData.favorite;
                this.updatingItem = true;
                this.localSelectedItemData = this.selectedItemData;
                this.folderID = this.selectedItemData.folderID;
                this.loginUrls = urls ? JSON.parse(urls) : [''];
                this.isFavorite = favorite === '1' ? true : false;
            } else {
                this.localSelectedItemData = {};
                this.updatingItem = false;
                this.loginUrls = [''];
                this.folderID = null;
            }
        },
        folderData() {
          this.localFolderData = this.folderData;
        }
    },
    computed: {
        modalTitle() {
            return this.updatingItem ? 'Update Item' : 'Add Item';
        },
        passwordVisibility() {
          return this.isPasswordVisible ? 'text' : 'password';
        }
    },
    methods: {
        // Toaster
        showToast(message, type) {
          this.$toasted.show(message, {
            theme: "toasted-primary",
            position: "top-center",
            duration: 3000,
            type: type,
          });
        },
        inputFieldsValidation() {
            if (!this.localSelectedItemData.name) {
                this.showToast('Name is required', 'error');
                return false;
            }
            if (!this.localSelectedItemData.username) {
                this.showToast('Username is required', 'error');
                return false;
            }
            if (!this.localSelectedItemData.password) {
                this.showToast('Password is required', 'error');
                return false;
            }
            return true;
        },
        handleItem( id = null ) {
            if(!this.inputFieldsValidation()){
              return;
            }
            const favorite = this.isFavorite ? 1 : 0;
            const params = {
                'id': id,
                'name': this.localSelectedItemData.name,
                'folderID': this.folderID,
                'username': this.localSelectedItemData.username,
                'password': this.localSelectedItemData.password,
                'urls': this.loginUrls,
                'notes': this.localSelectedItemData.notes,
                'favorite': favorite,
            };
            this.localSelectedItemData = {};
            this.loginUrls = [''];
            this.folderID = null;
            this.closeModal();
            this.$emit('add-or-update-item', params);
        },
        handleDeleteItem() {
            this.closeModal();
            this.$emit('delete-item', this.localSelectedItemData.id);
        },
        addNewField(){
            this.loginUrls = [...this.loginUrls, ''];
        },
        togglePasswordVisibility() {
            this.isPasswordVisible = !this.isPasswordVisible;
        },
        copyToClipboard ( text ) {
            navigator.clipboard.writeText(text);
        },
        closeModal() {
            this.$emit('toggle-modal');
        },
    },
    created() {
      Vue.use(Toasted);
    },
}
</script>