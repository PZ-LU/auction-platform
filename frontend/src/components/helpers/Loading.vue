<!-- Spinning SVG  -->
<template>
  <v-app>
    <v-container class="loading" />
  </v-app>
</template>

<style scoped>
  .loading {
    background-image: url("../../assets/loading.svg");
    position: absolute;
    width: 30px;
    height: 30px;
    padding: 0;
    margin: 0;
    -webkit-animation:spin 4s linear infinite;
    -moz-animation:spin 4s linear infinite;
    animation:spin 4s linear infinite;
  }
  @-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
  @-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
  @keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
</style>