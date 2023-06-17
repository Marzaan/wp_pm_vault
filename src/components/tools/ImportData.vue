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
      const ajaxUrl = window.ajax_object.ajax_url;
      const nonce = window.ajax_object.nonce;
      const importAction = 'import_endpoints';

      if (this.inputFile === '') {
        console.log("No file selected.");
        return;
      }

      const dataToSubmit = new FormData();
      dataToSubmit.append('action', importAction);
      dataToSubmit.append('route', 'import_items');
      dataToSubmit.append('file', this.inputFile);
      dataToSubmit.append('nonce', nonce);
      console.log("dataToSubmit", dataToSubmit);

      window.jQuery.ajax({
        url: ajaxUrl,
        data: dataToSubmit,
        method: 'POST',
        contentType: false,
        processData: false,
        success: (response) => {
          if (response.success) {
            console.log(response);
          } else {
            console.log("Server Error", response);
          }
        }
      })
      .always(function(res) {
          console.log("res", res);
          // remove loading image maybe
        })
        .fail(function(res) {
          console.log("fail", res);
          // handle request failures
        });
    }
  }
};
</script>
