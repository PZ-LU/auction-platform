<!-- Uniform modal dialog DEPRECATED -->
<template>
  <div
    class="modal"
    @click="closeModal()"
  >
    <div
      class="dialog"
    >
      <slot
        @closeModal="closeModal()"
      />
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    closeModal () {
      this.$emit('closeModal')
    }
  }
}
</script>

<style scoped>
  .dialog {
    position: relative;
    max-width: 500px;
    left: 0;
    margin-left: auto; 
    margin-right: auto; 
    right: 0;
    overflow: auto;
  }
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    margin: 0;
    min-width: 100vw;
    background-color:rgba(0, 0, 0, 0.561);
    z-index: 9;
  }
</style>