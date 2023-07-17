<template>
    <div class="accordion-item">
      <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true"
                aria-controls="panelsStayOpen-collapseTwo">
          ShortCodes
        </button>
      </h2>
      <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show"
           aria-labelledby="panelsStayOpen-headingTwo">
        <div class="accordion-body">
          <ul class="list-group list-group-flush">
            <li :class="['list-group-item', 'side-menu-item']" @click="copyToClipboard('[pm_vault_form]')">
                <i class="fa fa-clone cursor-pointer"></i> [pm_vault_form]
            </li>
            <li :class="['list-group-item', 'side-menu-item']" @click="copyToClipboard('[pm_vault_items]')">
                <i class="fa fa-clone cursor-pointer"></i> [pm_vault_items]
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "ShortCodeBar",
    methods: {
      copyToClipboard( shortcode ){
      this.$showToast('Shortcode copied', 'success');
      navigator.clipboard.writeText(shortcode);
    }
    }
  };
  </script>
  
  <style>
  </style>
    