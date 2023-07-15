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
      quests: [
      ],
      quest: null,
      states: []
    };
  },
  methods: {
    open(){
      this.loadQuests();
      if (this.quest){
        this.loadQuest(this.quest.id);
      }
    },
    loadQuests(){
      let uri = '/quests';
      axios.get(uri).then((response) => {
        this.quests = response.data;
      });
    },
    loadQuest(quest_id){
      let uri = '/quest/'+quest_id;
      axios.get(uri).then((response) => {
        this.quest = response.data.quest;
        this.states = response.data.states;
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
          <img class="h-10 w-10 m-auto" src="/public/open_emoji/lite_colored/light_bulb.png">
          <h2 class="p-3">Квесты</h2>
        </div>
        <div class="text-right p-3">
          <button class="hover:shadow-lg" @click="$emit('close')"><img class="h-6 w-6 m-auto" src="/public/open_emoji/lite_colored/cross_mark.png"></button>
        </div>
      </div>
    </template>
    <template v-slot:body>
      <div class="grid grid-cols-2 lg:grid-cols-5 h-full w-full">
        <div class="col-span-2 h-[30vh] lg:col-span-2 lg:h-full   overflow-y-scroll">
          <div class="flex h-full" v-if="!quests || !quests.length" >
            <p class="text-2xl mx-auto mt-4 text-slate-400 font-bold">Квесты отсутствуют</p>
          </div>
          <ul id="array-rendering">
            <li @click="loadQuest(quest.id)" class="h-8 bg-slate-300 active:bg-slate-600 focus:bg-slate-600 m-0.5 hover:bg-slate-500 rounded shadow flex" v-for="quest in quests">
              <p  class="ml-2 my-auto flex">
                <img class="h-6" :src="'/open_emoji/lite_colored/'+(quest.completed ? 'check_mark_button' : 'white_question_mark')+'.png'">
                {{ quest.name }}
              </p>
            </li>
          </ul>
        </div>
        <div class="col-span-2 h-[54vh]  lg:col-span-3 lg:h-full bg-gray-200">
          <div class="flex h-full" v-if="!quest" >
            <p class="text-4xl m-auto text-slate-500 font-bold">Выберите квест</p>
          </div>
          <div v-if="quest" class="h-full w-full bg-no-repeat bg-center flex  bg-[url('/open_emoji/svg/scroll_alternative.svg')]">
            <div class="quest-info my-16 lg:my-14 2xl:my-20 mx-12 lg:mx-20 2xl:mx-24  relative w-full" >
              <div id="quest-info-data" class="overflow-y-auto max-h-full absolute">
                  <p class="font-bold">{{quest.name}}</p>
                  <p class="italic">{{quest.description}}</p>
                  <ul class="">
                    <li class="flex" v-for="state in states">
                      <img class="h-6" :src="'/open_emoji/lite_colored/'+(state.completed ? 'check_mark_button' : 'radio_button')+'.png'">
                      {{state.name}}
                    </li>
                  </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </modal>

</template>

<style>
#quest-info-data::-webkit-scrollbar {
  width: 5px;               /* ширина scrollbar */
}
#quest-info-data::-webkit-scrollbar-track {
  background: #000000;        /* цвет дорожки */
}
#quest-info-data::-webkit-scrollbar-thumb {
  background-color: #000000;    /* цвет плашки */
  border-radius: 20px;       /* закругления плашки */
  border: 3px solid #b0642e;  /* padding вокруг плашки */
}
</style>
