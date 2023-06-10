<template>
    <div>
      <HeaderSection />
      <router-view></router-view>
    </div>
</template>

<script>

import Header from '../components/header/Header.vue';
export default {
    name: "MainLayout",
    components: {
      HeaderSection: Header,
    },
};  
</script>
