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
import Vue from 'vue';
import Toasted from 'vue-toasted';
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

    // Toaster
    showToast(message, type) {
      this.$toasted.show(message, {
        theme: "toasted-primary",
        position: "top-center",
        duration: 3000,
        type: type,
      });
    },

    handleFileSubmit(){
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const importAction = 'import_endpoints';

      if (this.inputFile === '') {
        this.showToast('No file selected.', 'error');
        return;
      }

      const dataToSubmit = new FormData();
      dataToSubmit.append('action', importAction);
      dataToSubmit.append('route', 'import_items');
      dataToSubmit.append('file', this.inputFile);
      dataToSubmit.append('nonce', nonce);

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST',
        contentType: false,
        processData: false
      })
      .then(() => {
        this.showToast('Items Imported Successfully', 'success');
        router.push({ path: '/' });
      })
      .fail( error => {
        this.showToast(error.responseJSON.data.message, 'error');
      });
    }
  },
  created() {
    Vue.use(Toasted);
  },
};
</script>
