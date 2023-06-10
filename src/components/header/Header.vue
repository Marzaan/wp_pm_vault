<template>
    <div>
        <div class="container page-container">
            <div class="nav-container shadow p-2 bg-white rounded d-flex">
                <router-link to="/" class="nav-link link-item nav-item">Home</router-link>
                <router-link to="/tools" class="nav-link link-item nav-item">Tools</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Header'
}
</script>

<style>
@import '../../assets/styles/header.scss';
</style>