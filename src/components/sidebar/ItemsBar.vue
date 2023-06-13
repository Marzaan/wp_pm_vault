<template>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
      <button class="accordion-button" type="button" data-bs-toggle="collapse"
              data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true"
              aria-controls="panelsStayOpen-collapseTwo">
        Items
      </button>
    </h2>
    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show"
         aria-labelledby="panelsStayOpen-headingTwo">
      <div class="accordion-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item side-menu-item" @click="handleSelectMenu($event, 'items')"><i
              class="fa fa-building"></i> All Items
          </li>
          <li class="list-group-item side-menu-item" @click="handleSelectMenu($event, 'favorite')"><i
              class="fa fa-star"></i> Favorites
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    selectMenu: Object,
    setSelectMenu: Function,
  },
  methods: {
    handleSelectMenu(event, type, value) {
      this.setSelectMenu({
        menuType: type,
        typeValue: value || this.selectMenu.typeValue,
      });
    },
  },
};
</script>

<style>
</style>
  