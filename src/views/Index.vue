<template>
    <div>
      <home-view/>
    </div>
</template>

<script>

import Header from '../components/layout/Header.vue';
import Home from './home/Home.vue';
import Footer from '../components/layout/Footer.vue';
console.log("IndexView");
export default {
    name: "IndexView",
    components: {
      HeaderSection : Header,
      HomeView : Home,
      FooterSection : Footer
    },
};
</script>
