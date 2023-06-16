<template>
  <div class="container page-container p-3">
    <div class="row">
      <div class="col-sm-6 col-md-3 mb-3">
        <div class="card">
          <div class="card-header text-uppercase fw-bold">
            Tools
          </div>
          <ul class="list-group tools-items list-group-flush">
            <li :class="['list-group-item', {active: selectMenu === 'generator'}]" @click="setSelectMenu('generator')">
              Generator
            </li>
            <li :class="['list-group-item', {active: selectMenu === 'import'}]" @click="setSelectMenu('import')">
              Import Data
            </li>
            <li :class="['list-group-item', {active: selectMenu === 'export'}]" @click="setSelectMenu('export')">
              Export Data
            </li>
          </ul>
        </div>
      </div>
      <div class="col-sm-6 col-md-9">
        <div class="tools-page m-auto shadow p-3 bg-white rounded">
          <component :is="getComponentName(selectMenu)"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Generator from "../../components/tools/Generator.vue";
import ImportData from "../../components/tools/ImportData.vue";
import ExportData from "../../components/tools/ExportData.vue";

export default {
  name: "Tools",
  components: {
    Generator,
    ImportData,
    ExportData,
  },
  data() {
    return {
      selectMenu: 'generator',
    };
  },
  computed: {
    getComponentName() {
      return (selectMenu) => {
        switch (selectMenu) {
          case 'import':
            return ImportData;
          case 'export':
            return ExportData;
          default:
            return Generator;
        }
      };
    },
  },
  methods: {
    setSelectMenu( menu ) {
      this.selectMenu = menu;
    }
  }
};
</script>

<style>
@import '../../assets/styles/tools.scss';
</style>