<template>
  <div class="container">
    <h3>Export Data</h3>
    <hr class="bg-danger border-1 border-top border-dark"/>
    <div class="card text-dark bg-light mb-3">
      <div class="card-body">
        <h5 class="card-title">EXPORTING INDIVIDUAL VAULT</h5>
        <p class="card-text">Only vault item information will be exported and will not include associated password
          history or attachments.</p>
      </div>
    </div>
    <button @click="handleSubmit" class="btn btn-primary hover-btn">
      Export Data
    </button>
  </div>
</template>

<script>
import Vue from 'vue';
import Toasted from 'vue-toasted';
export default {
  name: "ExportData",
  data() {
    return {
      exportData: [],
    };
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

    handleSubmit() {
      const url = window.URL.createObjectURL(new Blob([this.exportData]));
      const currentTime = new Date();
      const fileName = "export_" + currentTime.getTime() + ".csv";
      const link = document.createElement("a");
      link.href = url;
      link.setAttribute("download", fileName);
      document.body.appendChild(link);
      link.click();
    },

    /**** AJAX Call ****/
    getDataForExport() {
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const exportAction = 'export_endpoints';
      window.jQuery.ajax({
        url: ajaxUrl,
        data: {
          action: exportAction,
          route: 'export_items',
          nonce: nonce,
        },
        method: 'GET'
      })
      .then( response => {
        this.exportData = response.data;
      })
      .fail( error => {
        this.showToast(error.responseJSON.data.message, 'error');
        console.log("failed", error.responseJSON.data.message);
      });
    }
  },
  created() {
    Vue.use(Toasted);
  },
  mounted() {
    this.getDataForExport();
  },
};
</script>
