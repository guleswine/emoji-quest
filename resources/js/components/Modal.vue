<script>
export default {
  props: {
    show: Boolean,

  },
  data() {
    return {
      tab: 1,
      left_equipment: [],
      right_equipment: [],
      items_inventory: [],
      equipment_inventory: [],
      resources_inventory: [],
      appearance_inventory: [],
      hero: null,
    };
  },
  methods: {
    currentTab: function (tabNumber) {
      this.tab = tabNumber;
    },
    loadHero(){
      let uri = '/getplayerhero';
      axios.get(uri).then((response) => {
        this.hero = response.data;
      });
    },
    loadEquipment(){
      let uri = '/getleftsideequipment';
      axios.get(uri).then((response) => {
        this.left_equipment = response.data;
      });
      uri = '/getrightsideequipment';
      axios.get(uri).then((response) => {
        this.right_equipment = response.data;
      });
    },
    loadInventory(){
      let uri = '/getinventory/items';
      axios.get(uri).then((response) => {
        this.items_inventory = response.data;
      });
      uri = '/getinventory/equipment';
      axios.get(uri).then((response) => {
        this.equipment_inventory = response.data;
      });
      uri = '/getinventory/resources';
      axios.get(uri).then((response) => {
        this.resources_inventory = response.data;
      });
      uri = '/getinventory/appearance';
      axios.get(uri).then((response) => {
        this.appearance_inventory = response.data;
      });
    },
    startDragInventory(evt, hero_inventory) {
      evt.dataTransfer.dropEffect = 'move'
      evt.dataTransfer.effectAllowed = 'move'
      evt.dataTransfer.setData('entity_id', hero_inventory.id)
      evt.dataTransfer.setData('entity_type', 'inventory_slot')
    },
    onDropInventory(evt, hero_inventory) {
      let entity_id = evt.dataTransfer.getData('entity_id');
      let entity_type = evt.dataTransfer.getData('entity_type');
      axios.post('/ondropinventory',{id:hero_inventory.id,entity_id:entity_id,entity_type:entity_type}).then((response) => {
        this.loadInventory();
        if (entity_type=='hero_equipment'){
          this.loadEquipment();
        }
      });
    },
    startDragEquip(evt, hero_equipment) {
      evt.dataTransfer.dropEffect = 'move'
      evt.dataTransfer.effectAllowed = 'move'
      evt.dataTransfer.setData('entity_id', hero_equipment.id)
      evt.dataTransfer.setData('entity_type', 'hero_equipment')
    },
    onDropEquip(evt, hero_equipment) {
      let entity_id = evt.dataTransfer.getData('entity_id');
      let entity_type = evt.dataTransfer.getData('entity_type');
      axios.post('/ondropequipment',{id:hero_equipment.id,entity_id:entity_id,entity_type:entity_type}).then((response) => {
        //console.log(response.data);
        this.loadEquipment();
        if (entity_type=='inventory_slot'){
          this.loadInventory();
        }
      });
    },
  },
}
</script>

<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container shadow bg-white mx-auto w-screen h-screen lg:h-[60vh] lg:w-[70vw]">
          <div class="h-[6vh] lg:h-[8%] w-full bg-slate-100 grid grid-cols-2">
            <div><h2 class="p-3">Инвентарь</h2></div>
            <div class="text-right p-3">
              <button class="hover:shadow-lg" @click="$emit('close')"><img class="h-6 w-6 m-auto" src="/public/open_emoji/lite_colored/cross_mark.png"></button>
            </div>
          </div>

          <div class="modal-body grid grid-cols-2 h-[84vh] lg:h-[92%] w-full">
            <!-- Окно персонажа -->
            <div class="col-span-2 h-[42vh] lg:col-span-1 lg:h-full  bg-gray-200">
              <div class="h-full grid grid-cols-3">
                <div class="grid max-h-[90%] ">
                  <div class="m-auto bg-slate-100 border-4 w-16 h-16 border-gray-500 bg-white rounded" @drop="onDropEquip($event, equip)" @dragover.prevent @dragenter.prevent v-for="equip in left_equipment">
                    <img v-if="equip.emoji" draggable="true" @dragstart="startDragEquip($event, equip)" class="m-auto" :src="'/open_emoji/lite_colored/'+equip.emoji+'.png'">
                  </div>
                </div>
                <div >
                  <div v-if="hero" class="grid grid-cols-3 h-1/3 ">
                    <div class="flex"><img class="h-8 w-8 m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/red_heart.png"></div>
                    <div class="col-span-2 place-self-center">{{hero.health_current}}</div>
                    <div class="flex"><img class="h-8 w-8  m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/dagger.png"></div>
                    <div class="col-span-2 place-self-center">{{hero.attack_current}}</div>
                    <div class="flex"><img class="h-8 w-8  m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/brown_shield.png"></div>
                    <div class="col-span-2 place-self-center">{{hero.armor_current}}</div>
                    <div class="flex"><img class="h-8 w-8  m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/game_die.png"></div>
                    <div class="col-span-2 place-self-center">{{hero.action_points_current}}</div>
                  </div>
                  <div class="flex place-content-center">
                    <img class=" 2xl:h-80  2xl:w-80 h-52 w-52 m-auto max-w-none" src="/public/open_emoji/colored/man_standing.png">
                  </div>

                </div>
                <div class="grid max-h-[90%] ">
                  <div class="m-auto bg-slate-100 border-4 w-16 h-16 border-gray-500 bg-white rounded" @drop="onDropEquip($event, equip)" @dragover.prevent @dragenter.prevent v-for="equip in right_equipment">
                    <img v-if="equip.emoji" draggable="true" @dragstart="startDragEquip($event, equip)"  class="m-auto" :src="'/open_emoji/lite_colored/'+equip.emoji+'.png'">
                  </div>
                </div>
              </div>
            </div>
            <!-- Рюкзак -->
            <div class="col-span-2 h-[42vh]  lg:col-span-1 lg:h-full">
              <div class="container mx-auto">
                <ul class="flex mt-2 justify-center space-x-1 text-white">
                  <li>
                    <button
                        @click="currentTab(1)"
                        :class="{ 'bg-slate-600': tab === 1 }"
                        class="inline-block px-2 py-2 bg-slate-400 active:bg-slate-600  hover:bg-slate-500 text-white rounded"
                    >
                      Предметы
                    </button>
                  </li>
                  <li>
                    <button
                        @click="currentTab(2)"
                        :class="{ 'bg-slate-600': tab === 2 }"
                        class="inline-block px-2 py-2 bg-slate-400 active:bg-slate-600  hover:bg-slate-500 text-white rounded"
                    >
                      Экипировка
                    </button>
                  </li>
                  <li>
                    <button
                        @click="currentTab(3)"
                        :class="{ 'bg-slate-600': tab === 3 }"
                        class="inline-block px-2 py-2 bg-slate-400 active:bg-slate-600  hover:bg-slate-500 text-white rounded"
                    >
                      Ресурсы
                    </button>
                  </li>
                  <li>
                    <button
                        @click="currentTab(4)"
                        :class="{ 'bg-slate-600': tab === 4 }"
                        class="inline-block px-2 py-2 bg-slate-400 active:bg-slate-600  hover:bg-slate-500 text-white rounded"
                    >
                      Внешний вид
                    </button>
                  </li>
                </ul>
                <div class="mt-2 bg-white text-center">
                  <div class="inline-grid grid-cols-5 " v-if="tab === 1">
                    <div class="m-auto bg-slate-100 border-2 w-14 h-14 2xl:w-20 2xl:h-20 border-gray-500 bg-white rounded" @drop="onDropInventory($event, item)" @dragover.prevent @dragenter.prevent v-for="item in items_inventory">
                      <img v-if="item.emoji" draggable="true" @dragstart="startDragInventory($event, item)" class="m-auto" :src="'/open_emoji/lite_colored/'+item.emoji+'.png'">
                    </div>
                  </div>
                  <div class="inline-grid grid-cols-5 " v-if="tab === 2">
                    <div class="m-auto bg-slate-100 border-2 w-14 h-14 2xl:w-20 2xl:h-20 border-gray-500 bg-white rounded" @drop="onDropInventory($event, item)" @dragover.prevent @dragenter.prevent v-for="item in equipment_inventory">
                      <img v-if="item.emoji" draggable="true" @dragstart="startDragInventory($event, item)" class="m-auto" :src="'/open_emoji/lite_colored/'+item.emoji+'.png'">
                    </div>
                  </div>
                  <div class="inline-grid grid-cols-5 " v-if="tab === 3">
                    <div class="m-auto bg-slate-100 border-2 w-14 h-14 2xl:w-20 2xl:h-20 border-gray-500 bg-white rounded" @drop="onDropInventory($event, item)" @dragover.prevent @dragenter.prevent v-for="item in resources_inventory">
                      <img v-if="item.emoji" draggable="true" @dragstart="startDragInventory($event, item)" class="m-auto" :src="'/open_emoji/lite_colored/'+item.emoji+'.png'">
                    </div>
                  </div>
                  <div class="inline-grid grid-cols-5" v-if="tab === 4">
                    <div class="m-auto bg-slate-100 border-2 w-14 h-14 2xl:w-20 2xl:h-20 border-gray-500 bg-white rounded" @drop="onDropInventory($event, item)" @dragover.prevent @dragenter.prevent  v-for="item in appearance_inventory">
                      <img v-if="item.emoji" draggable="true" @dragstart="startDragInventory($event, item)"  class="m-auto" :src="'/open_emoji/lite_colored/'+item.emoji+'.png'">
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
