<script setup>

import { ref, onMounted, reactive } from 'vue';

const showme = ref(false);

const clickme = () => {
    showme.value = !showme.value;
};

</script>

<template>
    <button type="button" @click.prevent="clickme">
    Show settings
  </button>


    <h1 v-if="showme"> Settings page </h1>
</template>