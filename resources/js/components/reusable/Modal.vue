<script>
export default {
  props: {
    show: Boolean,
    height: String,
      width: String
  }
}
</script>
<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container shadow bg-white mx-auto " :class="height+' '+width" >
          <div class="h-[6vh] lg:h-[10%] 2xl:h-[8%] w-full ">
            <slot name="header"></slot>
          </div>

          <div class="modal-body block h-[84vh] lg:h-[90%] 2xl:h-[92%] w-full  ">
            <slot name="body"></slot>
          </div>

        </div>
      </div>
    </div>
  </Transition>
</template>


<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter-from {
  opacity: 0;
}

.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
