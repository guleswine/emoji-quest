<script>
export default {
  props: {
    show: Boolean,

  },
  data() {
    return {
      question: null,
      answers: [],
      name:null,
      emoji:null
    };
  },
  methods: {
    clearQuestion(){
      this.question = null;
      this.answers = [];
    },
    loadUnit(){

    },
    loadQuestion(question_id) {

      let uri = '/question/'+question_id;
      axios.get(uri).then((response) => {
        this.question = response.data.question;
        this.answers = response.data.answers;
      });
    },
    selectAnswer(answer){
      let uri = '/answer/'+answer.id+'/select';
      axios.get(uri).then((response) => {
        if (response.data){
          this.question = response.data.question;
          this.answers = response.data.answers;
        }else{
          this.$emit('close');
        }
      });
    }
  },
}
</script>

<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container shadow bg-white mx-auto h-auto w-60 ">
          <div class="h-[6vh] lg:h-[8%] w-full bg-slate-100 grid grid-cols-2">
            <div class="p-2"><img  class="inline-block h-8 w-8 m-auto'"  v-bind:src="'/open_emoji/lite_colored/'+emoji+'.png'">{{name}}</div>
            <div class="text-right p-3">
              <button class="hover:shadow-lg" @click="$emit('close')"><img class="h-6 w-6 m-auto" src="/public/open_emoji/lite_colored/cross_mark.png"></button>
            </div>
          </div>

          <div class="modal-body  w-full">
            <div v-if="question" class="p-4">{{question.text}}</div>
            <div class="p-2">
              <div class="p-2 m-2 bg-slate-100 active:bg-slate-500  hover:bg-slate-300 rounded shadow-md hover:shadow-lg  active:shadow-lg" @click="selectAnswer(answer)" v-for="answer in answers">{{answer.text}}</div>
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
