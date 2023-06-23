<script>
export default {
  props: {
    show: Boolean,

  },
  data() {
    return {
      cell_id: null,
      create: false,
      read: false,
        note: null,
    };
  },
  methods: {
    startWrite(cell_id){
      this.create = true;
      this.cell_id = cell_id;
    },
    startRead(note_id){
      this.read = true;
      axios.get('/notes/'+note_id).then((response) =>{
            this.note = response.data;
      });
    },
    close(){
      this.create = false;
      this.read = false;
      this.$emit('close');
    },
    createNote(){
        let content = document.getElementById('note-message').value
        axios.post('cell/'+this.cell_id+'/notes',{content:content}).then((response) => {
            this.create = false;
            this.read = false;
            this.$emit('close');
        });
    },
  },
}
</script>

<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container shadow bg-white mx-auto h-auto w-80 ">
          <div class="h-[6vh] lg:h-[8%] w-full bg-slate-100 grid grid-cols-2">
            <div class="p-2"><img  class="inline-block h-8 w-8 m-auto'"  v-bind:src="'/open_emoji/lite_colored/memo.png'">
            </div>
            <div class="text-right p-3">
              <button class="hover:shadow-lg" @click="close"><img class="h-6 w-6 m-auto" src="/public/open_emoji/lite_colored/cross_mark.png"></button>
            </div>
          </div>

          <div class="modal-body  w-full">
            <div class="text-center pb-1" v-if="create">
              <textarea id="note-message" class="resize-none w-[90%]" maxlength="200" placeholder="Текст до 200 символов" rows="7"></textarea>
              <button class="bg-slate-400 px-2 py-1  active:bg-slate-600  hover:bg-slate-500 text-white rounded" @click="createNote">Написать</button>
            </div>
              <div class="text-center p-2" v-if="read">
                  <p v-if="note" class="whitespace-pre-wrap">{{note.message}}</p>
              </div>
            <!-- Окно персонажа -->
            <!-- Рюкзак -->
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
