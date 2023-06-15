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
                @set-select-menu = "setSelectMenu"
              />
            </li>
            <li
                class="list-group-item text-danger fw-bold text-center cursor-pointer"
                @click="">
              <i class="bi bi-trash"></i> Trash
            </li>
          </ul>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-9">
        <ItemList
          :select-menu = "selectMenu"
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
      selectMenu: {
        menuType : '',
        typeValue: ''
      },
    };
  },
  watch: {
    'selectMenu.typeValue': () => {
      window.scroll({ top: 0, left: 0, behavior: 'smooth' });
    }
  },
  methods: {
    setSelectMenu( type, value ){
      this.selectMenu.menuType = type;
      this.selectMenu.typeValue = value;
    }
  }
};
</script>

<style>
@import "../../assets/styles/home.scss";
</style>
