<template>
  <div class="container page-container">
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
        <div class="card">
          <div class="card-header text-uppercase fw-bold">
            Filter
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <ItemsBar
                :select-menu = "selectMenu"
                @set-select-menu = "setSelectMenu"
              />
              <FoldersBar
                :select-menu = "selectMenu"
                :folder-data = "folderData"
                @get-folders = "getFolders"
                @update-folder-data = "updateFolderData"
                @set-select-menu = "setSelectMenu"
              />
            </li>
          </ul>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-9">
        <ItemList
          :select-menu = "selectMenu"
          :folder-data = "folderData"
        />
      </div>
    </div>
  </div>
</template>

<script>
import ItemsBar from "../../components/sidebar/ItemsBar.vue";
import FoldersBar from "../../components/sidebar/FoldersBar.vue";
import ItemList from "../../components/items/ItemList.vue";

export default {
  name: "Home",
  components: {
    ItemsBar,
    FoldersBar,
    ItemList,
  },
  data() {
    return {
      folderData: [],
      selectMenu: {
        menuType : '',
        typeValue: ''
      }
    };
  },
  watch: {
    'selectMenu.typeValue': () => {
      window.scroll({ top: 0, left: 0, behavior: 'smooth' });
    }
  },
  methods: {
    /*********** Events **********/
    setSelectMenu( type, value ){
      this.selectMenu.menuType = type;
      this.selectMenu.typeValue = value;
    },
    updateFolderData( updatedData ){
      this.folderData = updatedData;
    },

    /********* AJAX Call *********/
    getFolders() {
      const folderAction = 'folder_endpoints';
      window.jQuery.ajax({
        url: this.$ajaxUrl,
        data: {
          action: folderAction,
          route: 'get_folders',
          nonce: this.$nonce
        },
        method: 'GET',
      })
      .then( response => {
          this.folderData = response.data;
      })
      .fail( error => {
        this.$showToast(error.responseJSON.data.message, 'error');
      });
    },
  },
  mounted() {
    this.getFolders();
  }
};
</script>

<style>
@import "../../assets/styles/home.scss";
</style>
