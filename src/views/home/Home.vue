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
              <ItemsBar/>
              <FoldersBar
                :folderData="folderData"
              />
            </li>
            <li
              class="list-group-item text-danger fw-bold text-center cursor-pointer"
              @click=""
            >
              <i class="bi bi-trash"></i> Trash
            </li>
          </ul>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-9">
        <ItemList/>
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
      loading: true,
      folderData: [],
    };
  },
  methods: {
    getFolders() {
        const ajaxUrl = window.ajax_object.ajax_url;
        const nonce = window.ajax_object.nonce;
        window.jQuery.ajax({
          url: ajaxUrl,
          type: 'post',
          data: {
            action: 'get_folders',
            nonce: nonce,
          },
          dataType: 'json',
          success: function(response){
            this.folderData = response;
            console.log(response);
          }
        });
    },
    getItems() {
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      window.jQuery.ajax({
        url: ajaxUrl,
        type: 'post',
        data: {
          action: 'get_items',
          nonce: nonce,
        },
        dataType: 'json',
        success: function(response){
          console.log(response);
        }
      });
    },
  },
  mounted() {
    console.log("HomeMounted");
    this.getFolders();
    this.getItems();
  },
};
</script>

<style>
@import "../../assets/styles/home.scss";
</style>
