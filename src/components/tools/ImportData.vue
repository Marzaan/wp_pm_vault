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
    <form onSubmit={handleSubmit}>
      <div class="form-group">
        <input
            type="file"
            class="form-control-file"
            id="importFile"
            @change=""
        />
      </div>
      <button type="submit" class="btn btn-primary hover-btn">
        Submit
      </button>
    </form>
  </div>
</template>

<script>
export default {
  name: "ImportData",
};
</script>
