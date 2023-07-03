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
export default {
  name: "ExportData",
  data() {
    return {
      exportData: [],
    };
  },
  methods: {
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
      window.jQuery.ajax({
        url: this.$ajaxUrl,
        data: {
          action: 'export_endpoints',
          route: 'export_items',
          nonce: this.$nonce,
        },
        method: 'GET'
      })
      .then( response => {
        this.exportData = response.data;
      })
      .fail( error => {
        this.$showToast(error.responseJSON.data.message, 'error');
      });
    }
  },
  mounted() {
    this.getDataForExport();
  },
};
</script>
