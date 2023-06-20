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
          <li :class="['list-group-item', 'side-menu-item', selectMenu.menuType === 'items' ? 'active-menu' : '']" @click="handleSelectMenu('items', 'items')"><i
              class="fa fa-building"></i>   All Items
          </li>
          <li :class="['list-group-item', 'side-menu-item', selectMenu.menuType === 'favorite' ? 'active-menu' : '']" @click="handleSelectMenu('favorite', 'favorite')"><i
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
    selectMenu: Object
  },
  emits: [
    'set-select-menu'
  ],
  methods: {
    handleSelectMenu( type, value ) {
      this.$emit('set-select-menu', type, value);
    }
  }
};
</script>

<style>
</style>
  