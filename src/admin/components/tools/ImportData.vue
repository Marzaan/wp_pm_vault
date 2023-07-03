<template>
  <div class="container">
    <h3>Import Data</h3>
    <hr class="bg-danger border-1 border-top border-dark"/>
    <div class="card text-dark bg-light mb-3">
      <div class="card-body">
        <p class="card-text">You can export a sample file to follow the expected format. This can help avoid any
          potential errors or failed submissions during the import process.</p>
      </div>
    </div>
    <p class="fw-bold">Select the import file</p>
      <div class="form-group">
        <input
            type="file"
            class="form-control-file"
            id="importFile"
            @change="setInputFile"
        />
      </div>
      <button @click="handleFileSubmit" class="btn btn-primary hover-btn">
        Submit
      </button>
  </div>
</template>

<script>
import router from "../../routes.js";
export default {
  name: "ImportData",
  data() {
    return {
      inputFile: ''
    };
  },
  methods: {
    setInputFile( e ) {
      this.inputFile = e.target.files[0];
    },

    handleFileSubmit(){
      if (this.inputFile === '') {
        this.$showToast('No file selected.', 'error');
        return;
      }

      const dataToSubmit = new FormData();
      dataToSubmit.append('action', 'import_endpoints');
      dataToSubmit.append('route', 'import_items');
      dataToSubmit.append('file', this.inputFile);
      dataToSubmit.append('nonce', this.$nonce);

      window.jQuery.ajax({
        url: this.$ajaxUrl,
        data: dataToSubmit,
        method: 'POST',
        contentType: false,
        processData: false
      })
      .then(() => {
        this.$showToast('Items Imported Successfully', 'success');
        router.push({ path: '/' });
      })
      .fail( error => {
        this.$showToast(error.responseJSON.data.message, 'error');
      });
    }
  }
};
</script>
