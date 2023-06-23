<script>
export default {
  props: {
    show: Boolean,

  },
  data() {
    return {
      tab: 'items',
      left_equipment: [],
      right_equipment: [],
      tabs:{
        items: 'Предметы',
        equipments: 'Экипировка',
        resources: 'Ресурсы',
        appearance: 'Внешность',
      },
      inventories:{
        items: [],
        equipments: [],
        resources: [],
        appearance: [],
      },
      item:null,
      hero_stats: null,
      hero: null,
    };
  },
  methods: {
    currentTab: function (tabNumber) {
      this.tab = tabNumber;
    },
    playSound (sound) {
      if(sound) {
        var audio = new Audio(sound);
        audio.play();
      }
    },
    open(){
      this.playSound('/audio/effects/open_bag.wav');
      this.loadHero();
      this.loadEquipment();
      this.loadInventory();
    },
    loadHero(){
      let uri = '/hero';
      axios.get(uri).then((response) => {
        this.hero = response.data;
      });
      axios.get('/hero/stats').then((response) => {
        this.hero_stats = response.data;
      });

    },
    loadEquipment(){
      let uri = '/leftsideequipment';
      axios.get(uri).then((response) => {
        this.left_equipment = response.data;
      });
      uri = '/rightsideequipment';
      axios.get(uri).then((response) => {
        this.right_equipment = response.data;
      });
    },
    showItemInfo(event,item){
      this.item = item;
      let screen_width = window.screen.width;
      let posX = event.clientX-30;
      let posY = event.clientY+20
      if (screen_width<(event.clientX+200)){
        posX=screen_width-200;
      }else{

      }
      let contextMenuOpen = document.getElementById("item-info");
      contextMenuOpen.style.left = posX + 'px';
      contextMenuOpen.style.top = posY + 'px';
      contextMenuOpen.style.display = 'block';
    },
    enterItemMenu(){
    },
    hideItemInfo(){
      let contextMenuOpen =  document.getElementById("item-info");
      contextMenuOpen.style.display = 'none';
    },
    loadInventory(){
      let uri = '/inventory/items';
      axios.get(uri).then((response) => {
        this.inventories.items = response.data;
      });
      uri = '/inventory/equipment';
      axios.get(uri).then((response) => {
        this.inventories.equipments = response.data;
      });
      uri = '/inventory/resources';
      axios.get(uri).then((response) => {
        this.inventories.resources = response.data;
      });
      uri = '/inventory/appearance';
      axios.get(uri).then((response) => {
        this.inventories.appearance = response.data;
      });
    },
    startDragInventory(evt, hero_inventory) {
      this.hideItemInfo();
      evt.dataTransfer.dropEffect = 'move'
      evt.dataTransfer.effectAllowed = 'move'
      evt.dataTransfer.setData('entity_id', hero_inventory.id)
      evt.dataTransfer.setData('entity_type', 'inventory_slot')
    },
    onDropInventory(evt, hero_inventory,area_id) {
      let entity_id = evt.dataTransfer.getData('entity_id');
      let entity_type = evt.dataTransfer.getData('entity_type');
      let area =  document.getElementById("drag-area-"+area_id);
      area.style.backgroundColor = 'rgb(241 245 249)';
      axios.post('/ondropinventory',{id:hero_inventory.id,entity_id:entity_id,entity_type:entity_type}).then((response) => {
        this.loadInventory();
        if (entity_type=='hero_equipment'){
          this.loadEquipment();
        }
      });
    },
    enterInventoryToDragArea(area_id){
      let area =  document.getElementById("drag-area-"+area_id);
      area.style.backgroundColor = 'rgb(148 163 184)';
    },
    leaveInventoryToDragArea(area_id){
      let area =  document.getElementById("drag-area-"+area_id);
      area.style.backgroundColor = 'rgb(241 245 249)';
    },
    dropToBasket(evt){
      let entity_id = evt.dataTransfer.getData('entity_id');
      let entity_type = evt.dataTransfer.getData('entity_type');
      axios.post('/drop-in-basket',{entity_id:entity_id,entity_type:entity_type}).then((response) => {

        this.loadEquipment();
        if (entity_type=='inventory_slot'){
          this.loadInventory();
        }
      });
    },
    enterToBasketArea(){
      let basket_shake = [
        {transform: 'rotate(-15.0deg)'},
        {transform: 'rotate(15.0deg)'},
        {transform: 'rotate(-10.0deg)'},
        {transform: 'rotate(10.0deg)'},
      ]
      basket.animate(basket_shake, {duration: 1000});
    },
    startDragEquip(evt, hero_equipment) {
      evt.dataTransfer.dropEffect = 'move'
      evt.dataTransfer.effectAllowed = 'move'
      evt.dataTransfer.setData('entity_id', hero_equipment.id)
      evt.dataTransfer.setData('entity_type', 'hero_equipment')
    },
    onDropEquip(evt, hero_equipment,area_id) {
      let entity_id = evt.dataTransfer.getData('entity_id');
      let entity_type = evt.dataTransfer.getData('entity_type');
      let area =  document.getElementById("drag-area-"+area_id);
      area.style.backgroundColor = 'rgb(241 245 249)';
      axios.post('/ondropequipment',{id:hero_equipment.id,entity_id:entity_id,entity_type:entity_type}).then((response) => {

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
        <div class="modal-container shadow bg-white mx-auto w-screen h-[90vh] lg:h-[65vh] lg:w-[65vw]">
          <div class="h-[6vh] lg:h-[5vh] w-full bg-slate-100 grid grid-cols-2">
            <div><h2 class="p-3">Инвентарь</h2></div>
            <div class="text-right p-3">
            <button class="hover:shadow-lg" @click="$emit('close')"><img class="h-6 w-6 m-auto" src="/public/open_emoji/lite_colored/cross_mark.png"></button>
            </div>
          </div>

          <div class="modal-body grid grid-cols-2 h-[84vh] lg:h-[92%] w-full">
            <!-- Окно персонажа -->
            <div class="col-span-2 h-[42vh] lg:col-span-1 lg:h-full  bg-gray-200">
              <div class="h-full grid grid-cols-12">
                <div class="grid max-h-[90%] col-span-3 2xl:col-span-4">
                  <div class="m-auto bg-slate-100 border-4 w-16 2xl:w-20 h-16 2xl:h-20 border-gray-500 bg-white rounded " @drop="onDropEquip($event, equip)" @dragover.prevent @dragenter.prevent v-for="equip in left_equipment">
                    <img v-if="equip.emoji" draggable="true" @dragstart="startDragEquip($event, equip)" class="m-auto" :src="'/open_emoji/lite_colored/'+equip.emoji+'.png'">
                  </div>
                </div>
                <div class="col-span-6 2xl:col-span-4">
                  <div  v-if="hero && hero_stats" class="h-1/3">
                    <div class="grid grid-cols-12 ">
                      <div class=" col-span-2 font-bold"><p>Ур:</p></div>
                      <div class="col-span-2 self-center">{{hero.lvl}}</div>
                      <div class="col-span-8 self-center"><p class="font-light">({{hero.experience}}/{{hero.experience_total}})</p></div>
                    </div>
                    <div  class="grid grid-cols-8 ">
                      <div class="flex col-span-1"><img class="h-6 lg:h-7 m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/red_heart.png"></div>
                      <div class="col-span-3 self-center">{{hero_stats.health.current}} ({{Math.floor((hero_stats.health.current/hero_stats.health.final)*100)}}%)</div>
                      <div class="flex col-span-1"><img class="h-6 lg:h-7  m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/books.png"></div>
                      <div class="col-span-3 self-center">{{hero.skill_points}}</div>
                      <div class="flex col-span-1"><img class="h-6 lg:h-7 m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/brown_shield.png"></div>
                      <div class="col-span-3 self-center">{{hero_stats.armor.current}}</div>
                      <div class="flex col-span-1"><img class="h-6 lg:h-7 m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/game_die.png"></div>
                      <div class="col-span-3 self-center">{{hero_stats.action_points.current}}</div>
                      <div class="flex col-span-1"><img class="h-6 lg:h-7 m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/dagger.png"></div>
                      <div class="col-span-3 self-center">{{hero_stats.attack.current}}</div>
                      <div class="flex col-span-1"><img class="h-6 lg:h-7 m-auto"  style="max-width: none;" src="/public/open_emoji/lite_colored/coin.png"></div>
                      <div class="col-span-3 self-center">{{hero.coins}}</div>
                    </div>
                  </div>
                  <div class="flex place-content-center">
                    <img class=" 2xl:h-80  2xl:w-80 h-52 w-52 m-auto max-w-none" src="/public/open_emoji/colored/man_standing.png">
                  </div>

                </div>
                <div class="grid max-h-[90%] col-span-3 2xl:col-span-4">
                  <div :id="'drag-area-equip-'+equip.id" class="m-auto bg-slate-100 border-4 w-16 2xl:w-20 h-16 2xl:h-20 border-gray-500 bg-white rounded"
                       @dragenter="enterInventoryToDragArea('equip-'+equip.id)" @dragleave="leaveInventoryToDragArea('equip-'+equip.id)"
                       @drop="onDropEquip($event, equip,'equip-'+equip.id)" @dragover.prevent v-for="equip in right_equipment">
                      <div  v-if="equip.emoji" @mouseenter="showItemInfo($event,equip)" @mouseleave="hideItemInfo"
                            draggable="true" @dragstart="startDragEquip($event, equip)"
                            class="m-auto select-none w-full h-full bg-no-repeat bg-center bg-cover draggable"
                            :style="'background-image: url(/open_emoji/lite_colored/'+equip.emoji+'.png);'">
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Рюкзак -->
            <div class="col-span-2 h-[42vh]  lg:col-span-1 lg:h-full">
              <div class="container mx-auto">
                <div class="grid grid-cols-12 m-auto lg:w-10/12">
                  <ul class="col-span-12 lg:col-span-10 flex mt-2 justify-center space-x-1 text-white">
                    <li v-for="(label, name) in tabs">
                      <button
                          @click="currentTab(name)"
                          class="inline-block px-2 py-2 active:bg-slate-600  hover:bg-slate-500 text-white rounded "
                          :class="[tab === name ? 'bg-slate-600' : 'bg-slate-400']"
                      >
                        {{label}}
                      </button>
                    </li>
                  </ul>
                </div>
                <div class="grid grid-cols-12 m-auto w-10/12 p-2">
                  <div class="col-span-10 mt-2 bg-white text-center">
                    <div  v-for="(inventory, name) in inventories">
                      <div class="inline-grid grid-cols-5 w-full " v-if="tab === name">
                        <div :id="'drag-area-slot-'+item.id"
                             class="flex aspect-square bg-slate-100 border-2 w-full h-full max-h-full border-gray-500 rounded select-none"
                             @drop="onDropInventory($event, item,'slot-'+item.id)" @dragover.prevent
                             @dragenter="enterInventoryToDragArea('slot-'+item.id)" @dragleave="leaveInventoryToDragArea('slot-'+item.id)"
                             v-for="item in inventory">
                          <div  v-if="item.emoji" @mouseenter="showItemInfo($event,item)" @mouseleave="hideItemInfo"
                               draggable="true" @dragstart="startDragInventory($event, item)"
                                class="m-auto select-none w-[85%] h-[85%] bg-no-repeat bg-cover draggable"
                              :style="'background-image: url(/open_emoji/lite_colored/'+item.emoji+'.png);'">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-span-2 mt-2 flex">
                    <div id="basket"
                        @dragenter="enterToBasketArea"
                         @drop="dropToBasket($event)"
                         @dragover.prevent
                        class="w-14 h-14 2xl:w-20 2xl:h-20 bg-no-repeat bg-center bg-cover self-end"
                        :style="'background-image: url(/open_emoji/lite_colored/delete.png);'">

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
  <div  id="item-info" class="context-menu-open text-center w-48" @mouseenter="enterItemMenu">
    <h3 v-if="item" class="py-2 font-semibold ">{{item.name}}</h3>
    <p v-if="item" class="italic ">{{item.description}}</p>

  </div>
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
