<script>

import Modal from './reusable/Modal.vue';

export default {
  components: {
    Modal
  },
  props: {
    show: Boolean,

  },
  data() {
    return {
      talents: [
      ]
    };
  },
  methods: {
    open(){
      this.loadTalents();
    },
    loadTalents(){
      let uri = '/talents';
      axios.get(uri).then((response) => {
        this.talents = response.data;
      });
    }
  }
}
</script>

<template>
  <modal :show="show" :height="'h-screen lg:h-[80vh]'" :width="'w-screen lg:w-[40vw]'">
    <template v-slot:header>
      <div class="grid grid-cols-4 bg-slate-100 w-full h-full">
        <div class="col-span-3 justify-self-start flex align-items-center">
          <img class="h-10 w-10 m-auto" src="/public/open_emoji/lite_colored/man_juggling.png">
          <h2 class="p-3">Talents</h2>
        </div>
        <div class="text-right p-3">
          <button class="hover:shadow-lg" @click="$emit('close')"><img class="h-6 w-6 m-auto" src="/public/open_emoji/lite_colored/cross_mark.png"></button>
        </div>
      </div>
    </template>
    <template   v-slot:body>
      <div class="bg-gray-200 h-full">
        <div class="grid grid-cols-1  p-1 lg:grid-cols-2 w-full">
          <div class="col-span-1" v-for="talent in talents">
            <div class="border-2 shadow-md m-0.5 h-10 "
                 :style="'background: linear-gradient(90deg, #87d2ee '+Math.round((talent.current_progress/talent.total_progress)*100)+'%, #ffffff 0%)'">
              <div class="grid grid-cols-10 h-full" >
                <div class="col-span-1 flex">
                  <img class="h-10 my-auto"  v-bind:src="'/open_emoji/lite_colored/'+talent.emoji+'.png'">
                </div>
                <div class="col-span-4 flex">
                  <p class="my-auto">{{talent.name}}</p>
                </div>
                <div class="col-span-3 flex">
                  <p class="my-auto">{{talent.current_progress}}/{{talent.total_progress}}</p>
                </div>
                <div class="col-span-2 flex">
                  <p class="my-auto">LVL - {{talent.level}}</p>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>
    </template>
  </modal>

</template>
