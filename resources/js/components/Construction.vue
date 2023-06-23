<script>
export default {
  props: {
    show: Boolean,

  },
  data() {
    return {
      tab: 'defence',
      blueprints: {
        defence:[],
        settlement:[]
      },
      defence_blueprints: [],
      settlement_blueprints: [],
      hero: null,
      blueprint_id: 0,
      selected_blueprint: null
    };
  },
  methods: {
    open(){
      this.loadBlueprints();
      this.loadHero();
    },
    currentTab: function (tab_name) {
      this.tab = tab_name;
    },
    loadHero(){
      let uri = '/hero';
      axios.get(uri).then((response) => {
        this.hero = response.data;
      });
    },
    loadBlueprints(){
      let uri = '/blueprints/buildings/defense';
      axios.get(uri).then((response) => {
        this.blueprints.defence = response.data;
      });
       uri = '/blueprints/buildings/settlement';
      axios.get(uri).then((response) => {
        this.blueprints.settlement = response.data;
      });
    },
    selectBlueprint(blueprint){
      this.blueprint_id = blueprint.id;
      this.selected_blueprint = blueprint;
    }
  },
}
</script>

<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container shadow bg-white mx-auto w-screen h-screen lg:h-[60vh] lg:w-[70vw]">
          <div class="h-[6vh] lg:h-[8%] w-full bg-slate-100 grid grid-cols-2">
            <div><h2 class="p-3">Постройка</h2></div>
            <div class="text-right p-3">
              <button class="hover:shadow-lg" @click="$emit('close')"><img class="h-6 w-6 m-auto" src="/public/open_emoji/lite_colored/cross_mark.png"></button>
            </div>
          </div>

          <div class="modal-body grid grid-cols-2 h-[84vh] lg:h-[92%] w-full">
            <!-- Окно выбранного чертежа -->
            <div class="col-span-2 h-[42vh] lg:col-span-1 lg:h-full  bg-gray-200">
              <div class="flex h-full" v-if="!selected_blueprint" >
                <p class="text-4xl m-auto text-slate-500 font-bold">Выберите чертеж</p>
              </div>
              <div class="pt-10" v-if="selected_blueprint" >
                <div class="h-full grid grid-cols-3">
                  <div class="grid grid-cols-2 h-1/3">
                    <p class="font-semibold col-span-2 text-center">Требуется</p>
                    <div class="flex"><img class="h-6 w-6 lg:h-8 lg:w-8 m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/coin.png"></div>
                    <div class="place-self-center">{{selected_blueprint.cost_coins}}/{{hero.coins}}</div>
                    <div class="flex"><img class="h-6 w-6 lg:h-8 lg:w-8 m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/hourglass_not_done.png"></div>
                    <div class="place-self-center">{{selected_blueprint.creation_duration}}</div>
                  </div>
                  <div class="flex place-content-center">
                    <img class=" h-32 w-32 max-w-none" :src="'/open_emoji/lite_colored/'+selected_blueprint.emoji+'.png'">
                  </div>
                  <div class="block place-self-center p-2">
                    <button class="p-2 inline-block bg-slate-400 active:bg-slate-600 hover:bg-slate-500 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:shadow-lg active:shadow-lg transition duration-150 ease-in-out"
                            id="show-modal" @click="$emit('buildBuilding',selected_blueprint.id)" ><img class="h-12 w-12 m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/hammer_and_wrench.png">Построить</button>
                  </div>
                </div>
                <div class="text-center">
                  <h3 class="py-2 font-semibold ">{{selected_blueprint.name}}</h3>
                  <p class="italic ">{{selected_blueprint.description}}</p>
                </div>

              </div>
            </div>
            <!-- Рюкзак -->
            <div class="col-span-2 h-[42vh]  lg:col-span-1 lg:h-full">
              <div class="container mx-auto">
                <ul class="flex mt-2 justify-center space-x-1 text-white">
                  <li>
                    <button
                        @click="currentTab('defence')"
                        class="inline-block px-2 py-2 active:bg-slate-600  hover:bg-slate-500 text-white rounded"
                        :class="[tab === 'defence' ? 'bg-slate-600' : 'bg-slate-400']"
                    >
                      Оборона
                    </button>
                  </li>
                  <li>
                    <button
                        @click="currentTab('settlement')"
                        class="inline-block px-2 py-2 active:bg-slate-600  hover:bg-slate-500 text-white rounded"
                        :class="[tab === 'settlement' ? 'bg-slate-600' : 'bg-slate-400']"
                    >
                      Поселение
                    </button>
                  </li>
                </ul>
                <div class="mt-2 bg-white text-center">
                  <div v-for="(selected_blueprints, name) in blueprints">
                    <div class="" v-if="tab === name">
                      <div class="flex h-full w-full" v-if="!selected_blueprints || !selected_blueprints.length" >
                        <p class="text-2xl m-auto text-slate-400 font-bold mt-8">Чертежи отутствуют</p>
                      </div>
                      <div class="inline-grid grid-cols-5 ">
                        <div
                            class="m-auto active:bg-slate-400  hover:bg-slate-300 border-2 w-14 h-14 2xl:w-20 2xl:h-20 border-gray-500 bg-white rounded"
                            :class="[blueprint.id === blueprint_id ? 'bg-slate-400' : 'bg-slate-100']"
                            v-for="blueprint in selected_blueprints">
                          <img v-if="blueprint.emoji" @click="selectBlueprint(blueprint)" class="m-auto" :src="'/open_emoji/lite_colored/'+blueprint.emoji+'.png'">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
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
