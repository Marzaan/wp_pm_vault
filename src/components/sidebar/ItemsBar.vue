<template>
    <div class="accordion-item">
      <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
          Items
        </button>
      </h2>
      <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingTwo">
        <div class="accordion-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item side-menu-item" @click="handleSelectMenu($event, 'items')"><i class="bi bi-bricks"></i> All Items</li>
            <li class="list-group-item side-menu-item" @click="handleSelectMenu($event, 'favorite')"><i class="bi bi-star"></i> Favorites</li>
            <li class="list-group-item side-menu-item" @click="handleSelectMenu($event, 'type', 1)"><i class="bi bi-globe"></i> Login</li>
            <li class="list-group-item side-menu-item" @click="handleSelectMenu($event, 'type', 2)"><i class="bi bi-credit-card-2-back"></i> Card</li>
            <li class="list-group-item side-menu-item" @click="handleSelectMenu($event, 'type', 3)"><i class="bi bi-person-vcard"></i> Identity</li>
            <li class="list-group-item side-menu-item" @click="handleSelectMenu($event, 'type', 4)"><i class="bi bi-sticky"></i> Secure note</li>
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
  