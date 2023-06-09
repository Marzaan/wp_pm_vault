<template>
    <div class="accordion-item">
      <h2 class="accordion-header" id="panelsStayOpen-headingThree">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
          Folders
        </button>
      </h2>
      <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingThree">
        <div class="accordion-body">
          <ul class="list-group list-group-flush">
            <template>
              <li v-for="(data, index) in folderData" :key="index" class="list-group-item d-flex justify-content-between align-items-center folder-list">
                <div
                  @click=""
                >
                  <i class="bi bi-folder2"></i>
                </div>
                <span>
                  <i class="bi bi-three-dots-vertical" @click=""></i>
                </span>
              </li>
            </template>
            <li class="list-group-item side-menu-item" @click=""><i class="bi bi-plus"></i> Add Folder</li>
          </ul>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  
  export default {
    name: "FoldersBar",
    props: {
      folderData: Array,
      setSelectMenu: Function,
    },
  };
  </script>
  
  <style>
  </style>
  