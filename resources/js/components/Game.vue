<template>
  <FlashMessage position="right top"  />
  <Teleport to="body">
    <inventory ref="inventory" :show="show.inventory" @close="close('inventory')"></inventory>
  </Teleport>
  <Teleport to="body">
    <dialogue ref="dialogue" :show="show.dialogue" @close="close('dialogue')" ></dialogue>
  </Teleport>
  <Teleport to="body">
    <construction ref="construction" :show="show.construction" @close="close('construction')" @buildBuilding="buildBuilding" ></construction>
  </Teleport>
  <Teleport to="body">
    <global-map ref="globalmap" :show="show.globalmap" @close="close('globalmap')" ></global-map>
  </Teleport>
  <Teleport to="body">
    <skills ref="skills" :show="show.skills" @close="close('skills')" ></skills>
  </Teleport>
  <Teleport to="body">
    <quests ref="quests" :show="show.quests" @close="close('quests')" ></quests>
  </Teleport>
  <Teleport to="body">
    <talents ref="talents" :show="show.talents" @close="close('talents')" ></talents>
  </Teleport>
  <Teleport to="body">
    <note ref="note" :show="show.note" @close="close('note')" ></note>
  </Teleport>
  <Teleport to="body">
      <education ref="education" :show="show.education" @close="closeEducation" ></education>
  </Teleport>
  <!--Sidebar with Dimmer -->
  <!-- Sidebar -->
  <events ref="events" :show="show.events"></events>
  <help :show="show.help"></help>
  <div class="min-h-screen bg-gray-200">
  <div class="bg-white 2xl:border-b border-gray-100" >
  <div class="overflow-hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" >
      <div class="flex justify-between transition-all duration-700 2xl:h-20" :class="[this.show.header ? 'h-20' : 'h-0']">
          <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 my-auto items-center">
                  <a href="/">
                    <logo-svg></logo-svg>
                  </a>
              </div>
              <!-- Settings Dropdown -->
              <div class="2xl:hidden flex items-center">
                  <p v-if="hero">{{hero.name}}</p>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                  <a class="inline-flex items-center px-1 pt-1 border-t-4 border-gray-400 text-m font-medium leading-5 text-gray-900 focus:outline-none focus:border-gray-700 transition duration-150 ease-in-out" href="/game">
                      Game
                  </a>
              </div>
          </div>
          <div class="ml-auto mr-0 flex items-center">

          </div>



          <!-- Hamburger -->
          <div class="ml-auto mr-0 flex items-center" >
              <button @click="changeSound"
                      class="inline-block bg-slate-100 active:bg-slate-400 m-1
          hover:bg-slate-300 text-white font-medium
           text-xs leading-tight uppercase rounded
             transition duration-150 ease-in-out"
                      id="show-modal" >
                  <img class="h-12 w-12 m-auto"  style="max-width: none;" :src="'/open_emoji/lite_colored/'+(this.settings.sound ? 'speaker_high_volume' : 'muted_speaker')+'.png'">
              </button>
                  <form method="POST" action="/logout">
                      <input type="hidden" name="_token" :value="csrf">

                      <a href="/logout" class="inline-block bg-slate-100 active:bg-slate-400 m-1
          hover:bg-slate-300 text-white font-medium
           text-xs leading-tight uppercase rounded
             transition duration-150 ease-in-out"
                                             onclick="event.preventDefault();
                                        this.closest('form').submit();">
                          <img class="h-12 w-12 inline" src="/public/open_emoji/lite_colored/inbox.png" title="Выход" >
                      </a>

                  </form>
          </div>
      </div>
  </div>
  </div>
  <div class="">
    <div class="h-8 max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div  class="px-4 overflow-hidden 2xl:hidden ">
        <button
            @click.prevent="toggleEvents()"
            class="w-5/12 h-8 p-1 my-auto rounded text-white bg-slate-400 text-center focus:outline-none hover:bg-gray-500 transition-color duration-300">
			<span class="block transform origin-center font-bold">Events</span>
        </button>

          <button
              @click.prevent="toggleHeader()"
              class="w-2/12 h-8 p-1 my-auto ">
              <span class="block transform origin-center font-bold rounded text-white bg-slate-400 text-center focus:outline-none hover:bg-gray-500 transition-color duration-300">
                  <img class="h-6 w-6 inline" src="/public/open_emoji/lite_colored/home_button.png"></span>
          </button>
        <button
            @click.prevent="toggleHelp()"
            class="w-5/12 float-right h-8 p-1 my-auto rounded text-white bg-slate-400 text-center focus:outline-none hover:bg-gray-500 transition-color duration-300">
			<span class="block transform origin-center font-bold">Help</span>
        </button>
      </div>
    </div>

    <div class="h-20 max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="grid grid-cols-5 h-full  overflow-hidden shadow-lg">
        <div class="col-span-2 bg-slate-100">
          <div class="px-1 py-1" v-if="status.cell" >
            <p class=""><img  v-if="status.cell.object_emoji || status.cell.emoji" class="h-8 w-8 inline"  style="max-width: none;" v-bind:src="'/open_emoji/lite_colored/'+(status.cell.object_emoji ?? status.cell.emoji)+'.png'">{{status.cell.object_name ?? status.cell.name}}</p>
            <p v-if="status.cell.object_attribute">
              <img class="h-6 w-6 inline"  style="max-width: none;" v-bind:src="'/open_emoji/lite_colored/'+status.attribute_emoji+'.png'">
              {{status.cell.object_attribute}}</p>
            <p class="">ID:{{status.cell.id}} x={{status.cell.x}},y={{status.cell.y}}</p>
          </div>
        </div>
        <div v-if="hero" class="col-span-3  " :class="{'bg-slate-100': (hero.state_name=='traveler'),'bg-red-50': (hero.state_name=='battle')}">
          <div class=" max-w-full overflow-x-auto whitespace-nowrap" v-if="status.battle">
            <div class="mx-1 px-1 shadow rounded inline-block align-top"
                 :class="{'bg-red-200': (fighter.type=='enemy'),'bg-lime-200': (fighter.type=='hero')}"
                 v-for="fighter in status.battle.queue">
              <div class="">
                  <img class="h-5 inline"  src="/public/open_emoji/lite_colored/red_heart.png">
                  <p class="inline">{{fighter.health}}</p>
                  <img class="h-6 inline"  src="/public/open_emoji/lite_colored/game_die.png">
                  <p class="inline">{{fighter.action_points}}</p>
              </div>
              <img class="h-8 mx-auto" :src="'/open_emoji/lite_colored/'+fighter.emoji_name+'.png'">
              <p class="whitespace-nowrap text-center">{{fighter.name}}</p>
            </div>

          </div>
        </div>

      </div>
    </div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-lg ">
        <div id="map-border" class="grid p-6 border-b border-gray-200 overflow-scroll h-[70vh] " :style="'background-color: '+(map ? map.ambient_color : 'rgb(255 255 255)')">
          <div id="map" class="m-auto flex flex-wrap  " style="line-height: 0px;" :style="'width: '+(3*map_local_width)+'rem;'">
            <div :id="'cell-border-'+cell.id"  class="relative cell-border h-12 w-12  select-none bg-transparent hover:bg-gray-400 focus:bg-gray-400 text-gray-800 font-semibold border  hover:border-transparent rounded"
                 v-for="cell in cells" >
              <div :id="'cell-'+cell.id"
                   class="inline-flex h-full w-full bg-no-repeat bg-center"
                   :style="'background-size: '+cell.size/4+'rem; background-image: url(/open_emoji/lite_colored/'+cell.emoji+'.png);'"
                   v-on:click="selectCell(cell,$event)"
                   v-on:dblclick="priorityAction(cell)"
                   >
                  <img :id="'cell-img-'+cell.id" v-if="cell.object_emoji" v-bind:class="'h-'+cell.object_size+' w-'+cell.object_size+' m-auto'"  v-bind:src="'/open_emoji/lite_colored/'+cell.object_emoji+'.png'">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-lg rounded-b-lg">
        <div class="p-1 flex justify-center bg-white border-b border-gray-200" v-on:click="menuHide()">
            <action-button emoji="compass" @click="scrollToElement"></action-button>
            <action-button emoji="backpack" @click="open('inventory')"></action-button>
            <action-button emoji="openstreetmap" @click="open('globalmap')"></action-button>
            <action-button emoji="books" @click="open('skills')"></action-button>
            <action-button emoji="light_bulb" @click="open('quests')"></action-button>
            <action-button emoji="man_juggling" @click="open('talents')"></action-button>
        </div>
      </div>
    </div>
  </div>
  <div id="cell-menu" class="context-menu-open">
    <ul>
      <menu-item v-if="status.cell && status.cell.object_class=='Item'" @click="takeItem(status.cell)" emoji="palm_down_hand">Pick up</menu-item>
      <template v-if="hero && hero.state_name == 'battle'">
        <template v-if="status.cell && status.cell.object_type == 'enemy'">
            <menu-item  @click="attack(status.cell,'head')" emoji="crossed_swords">Attack head</menu-item>
            <menu-item  @click="attack(status.cell,'body')" emoji="crossed_swords">Attack body</menu-item>
            <menu-item  @click="attack(status.cell,'limbs')" emoji="crossed_swords">Attack limbs</menu-item>
        </template>
        <template v-if="status.cell && status.cell.object_class=='Hero'  && status.cell.object_id==hero.id">
          <menu-item  @click="defence(hero.id,'head')" emoji="brown_shield">Protect head</menu-item>
          <menu-item  @click="defence(hero.id,'body')" emoji="brown_shield">Protect body</menu-item>
          <menu-item  @click="defence(hero.id,'limbs')" emoji="brown_shield">Protect limbs</menu-item>
        </template>
        <menu-item v-if="status.cell" @click="moveToCell(status.cell.id)" emoji="footprints">Move</menu-item>
      </template>
      <template v-if="hero && hero.state_name == 'traveler'">
          <menu-item v-if="status.cell && status.cell.transfer_to_cell_id" @click="transferToCell(status.cell.id,status.cell.transfer_to_cell_id)" emoji="globe_with_meridians">Relocate</menu-item>
          <menu-item v-if="status.cell && status.cell.object_class=='Unit'" @click="interactionWithUnit(status.cell.object_id)" emoji="chats">Interact</menu-item>
          <menu-item v-if="status.cell && status.cell.object_class=='Note'" @click="readNote(status.cell.object_id)" emoji="magnifying_glass_tilted_left">Read</menu-item>
          <menu-item v-if="status.cell && status.cell.object_class=='Note'" @click="destroyNote(status.cell.id,status.cell.object_id)" emoji="delete">Erase</menu-item>
          <menu-item v-if="status.cell && !status.cell.transfer_to_cell_id" @click="moveToCell(status.cell.id)" emoji="footprints">Move</menu-item>
          <menu-item v-if="status.cell && status.cell.surface_type=='ground' && !status.cell.object_class && !status.cell.transfer_to_cell_id"
                     @click="open('construction')" emoji="hammer_and_wrench">Building</menu-item>

          <menu-item v-if="status.cell && skills && skills.notes && !status.cell.object_class" @click="writeNote(status.cell)" emoji="memo">Write a note</menu-item>
          <menu-item v-if="status.cell && status.cell.object_creator_hero_id == hero.id && status.cell.object_class=='Building'" @click="destroyBuilding(status.cell.id)" emoji="delete">Destroy</menu-item>

      </template>
        <menu-item v-if="status.cell && hero.id==1"><a :href="'/nova/resources/cells/'+status.cell.id+'/edit'" target="_blank">Edit</a></menu-item>
    </ul>
  </div>
    </div>


</template>



<script>
//components
import Inventory from './Inventory.vue'
import Dialogue from './Dialogue.vue'
import Construction from './Construction.vue'
import GlobalMap from './GlobalMap.vue'
import Skills from './Skills.vue'
import Quests from './Quests.vue'
import Talents from './Talents.vue'
import Help from './Help.vue'
import Events from './Events.vue'
import Note from './Note.vue'


import ActionButton from './reusable/Game/ActionButton.vue';
import MenuItem from './reusable/Game/MenuItem.vue';
import LogoSvg from './reusable/LogoSvg.vue';
//data
import Animations from "./animations";
import {random} from "lodash/number";
import Education from "./Education.vue";
import {Centrifuge} from "centrifuge";
export default {

  components: {
      LogoSvg,
      Education,
    Inventory,Dialogue,Construction,GlobalMap, Skills, Quests, Talents,
    ActionButton,Help,Events,Note

    ,MenuItem
  },
  data(){
    return{
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      cells: [],
      events: [],
      map: null,
        map_local_width: 11,
      hero: null,
      skills: null,
      status: {cell:null,attribute_emoji:null,battle: null},
      animations: [],
      dimmer: true,
      right: false,
      show: {
        inventory: false,
        dialogue: false,
        construction:false,
        globalmap: false,
        skills: false,
        quests: false,
        talents: false,
        help: false,
        events: false,
        note: false,
        education: false,
        header: false,
      },
        settings: {
            sound: true
        }
    }
  },

  created: function()
  {
    this.loadMap();
    this.loadCells();
    this.loadHero();
    this.loadSkills();
    // this.openSocket();
    this.openSocketCentrifuge();



  },
  mounted() {
      if (!localStorage.educated){
          this.show.education = true;
      }
      if (localStorage.sound == 'disable'){
          this.settings.sound = false;
      }
    this.$nextTick(function () {



    })

  },

  methods: {
    showNotfy(type,message){
      this.$flashMessage.show({
        type: type,
        text: message,
        time: 5000,
        blockClass: 'notify-block',
        contentClass: 'notify-content'
      });
    },
    loadSkills(){
      axios.get('/skills/names').then((response) => {
        this.skills = response.data;
      });
    },

    openSocketCentrifuge(){
        axios.get('/ws/token').then((response) => {
                let token  = response.data.token;
                let hero_id = response.data.hero_id;
                const subscribeTokenEndpoint = '/broadcasting/auth'

                const centrifuge = new Centrifuge(import.meta.env.VITE_CENTRIFUGO_CONNECTION, {
                    //CONNECTION_TOKEN must be obtained from Centrifuge::generateConnectionToken(...)
                    token: token
                })

    // Set the subscription
                const sub = centrifuge.newSubscription('hero.'+hero_id, {
                    getToken: function (ctx) {
                        return  new Promise((resolve, reject) => {
                            fetch(subscribeTokenEndpoint, {
                                method: 'POST',
                                headers: new Headers({ 'Content-Type': 'application/json' }),
                                body: JSON.stringify(ctx)
                            })
                                .then(res => {
                                    if (!res.ok) {
                                        throw new Error(`Unexpected status code ${res.status}`);
                                    }
                                    return res.json();
                                })
                                .then(data => {
                                    resolve(data.token);
                                })
                                .catch(err => {
                                    reject(err);
                                });
                        });
                    },
                })
            centrifuge.connect();
            sub.on('publication', (ctx) => {
                switch (ctx.data.event){
                    case "App\\Events\\GameEvent":
                        this.$refs.events.addEvent(ctx.data);
                        break;
                    case "App\\Events\\GameNotification":
                        this.showNotfy(ctx.data.type, ctx.data.message);
                        break;
                    case "App\\Events\\UnitMoved":
                        this.moveUnit(ctx.data.path,ctx.data.cell);
                        break;
                    case "App\\Events\\MoveEnemy":
                        this.moveUnit(ctx.data.path,ctx.data.cell);
                        break;
                    case "App\\Events\\BattleQueueMoved":
                        this.loadBattleStatus();
                        break;
                    case "App\\Events\\HeroDead":
                        this.loadMap();
                        this.loadCells();
                        this.loadHero();

                        break;
                    case "App\\Events\\BattleFinished":
                        this.loadCells();
                        this.loadHero();
                        break;
                    case "App\\Events\\UnitAttacked":
                        this.showAttakUnit(ctx.data.cell_id,ctx.data.damage);
                        break;
                    case "App\\Events\\UpdateCell":
                        let index = this.cells.findIndex(item => item.id == ctx.data.cell.id);
                        this.cells[index] = ctx.data.cell;
                        break;
                    default:
                        console.log(ctx.data);
                }
                // handle new Publication data coming from channel "news".

            });

            sub.subscribe();

        });
    },
    moveOtherHero(path){
    },
    showAttakUnit(cell_id,damage){
      this.playSound('/audio/effects/hit.mp3');
      let element = document.getElementById("cell-img-"+cell_id);
      let animation = element.animate(Animations.unit_attaked, {duration: 1000});
      const p_damage = document.createElement('p');
      p_damage.id='damage-'+cell_id;
      let left_position = Math.floor(Math.random() * (30 - 5) + 5);
      p_damage.style = 'position: absolute;' +
          'top: 20px;' +
          'text-shadow: 1px 0 #fff, -1px 0 #fff, 0 1px #fff, 0 -1px #fff, 1px 1px #fff, -1px -1px #fff, 1px -1px #fff, -1px 1px #fff;';
      p_damage.style.left = left_position+'px';

      p_damage.innerHTML = damage;

      document.getElementById("cell-border-"+cell_id).appendChild(p_damage);
      let start = Date.now();
      let timer = setInterval(function() {
        let timePassed = Date.now() - start;
        if (timePassed >= 1000) {
          clearInterval(timer);
          p_damage.remove();
          return;
        }
        p_damage.style.top=(parseInt(p_damage.style.top,10)-1)+'px';
      }, 40);
    },
    interactionWithUnit(unit_id){
        axios.get('/units/'+unit_id).then((response) => {
            let unit = response.data;
            this.startDialogue(unit);
        });

    },
    moveObjectBetweenCells(start_cell_id,finish_cell_id){
      let start_cell = this.cells.find(item => item.id == start_cell_id);
      let finish_cell = this.cells.find(item => item.id == finish_cell_id);
      finish_cell.object_emoji = start_cell.object_emoji;
      start_cell.object_emoji = null;
      finish_cell.object_name = start_cell.object_name;
      start_cell.object_name = null;
      finish_cell.object_size = start_cell.object_size;
      start_cell.object_size = null;
      finish_cell.object_class = start_cell.object_class;
      start_cell.object_class = null;
      finish_cell.object_type = start_cell.object_type;
      start_cell.object_type = null;
    },
    clearCell(cell_id){
      let cell = this.cells.find(item => item.id == cell_id);
      if(cell){
        cell.object_emoji = null;
        cell.object_name = null;
        cell.object_size = null;
        cell.object_class = null;
        cell.object_type = null;
        cell.object_id = null;
      }
    },
    updateCell(new_cell){
      let cell = this.cells.find(item => item.id == new_cell.id);
      if (cell){
        cell.object_emoji = new_cell.object_emoji;
        cell.object_name = new_cell.object_name;
        cell.object_class = new_cell.object_class;
        cell.object_type = new_cell.object_type;
        cell.object_size = new_cell.object_size;
        cell.object_id = new_cell.object_id;
      }

    },
    moveUnit(path,cell){
      let counter = 0;
      this.clearCell(path[0]);
      this.updateCell(cell);
      path.forEach(path_cell_id => {
        counter++;
        let cell = this.cells.find(item => item.id == path_cell_id);
        if (cell && cell.object_emoji == null) {
          let element = document.getElementById("cell-" + path_cell_id);

          element.animate(Animations.hero_steps, {duration: 1000, delay: 200*counter});
        }
      });

      this.$nextTick(() => {
        let finish_cell = document.getElementById("cell-img-" + path[path.length-1]);
        finish_cell.animate(Animations.hero_show, {duration: 1000});
      });

    },
    toggleEvents() {
      this.show.events = !this.show.events;
    },
    toggleHeader() {
        this.show.header = !this.show.header;
    },
    toggleHelp() {
      this.show.help = !this.show.help;
    },
    menuHide(){
      let contextMenuOpen =  document.getElementById("cell-menu");
      contextMenuOpen.style.display = 'none';
    },
    menuOpen(event){
      let contextMenuOpen = document.getElementById("cell-menu");
      contextMenuOpen.style.left = (event.clientX-30) + 'px';
      contextMenuOpen.style.top = (event.clientY+20) + 'px';
      contextMenuOpen.style.display = 'block';
    },
    playSound (sound) {
      if(sound && this.settings.sound) {
        var audio = new Audio(sound);
        audio.play();
      }
    },
    loadHero(){
      let uri = '/hero';
      axios.get(uri).then((response) => {
        this.hero = response.data;
          if (!this.status.cell && this.cells){
              let cell = this.cells.find(item => item.id == this.hero.cell_id);
              this.status.cell = cell;
              this.autoScrollToUnit();
          }
        if (this.hero.state_name == 'battle'){
          this.loadBattleStatus();
        }else{
          this.status.battle = null;
        }
      });
    },
    loadBattleStatus(){
      axios.get('/battle').then((response) => {
        this.status.battle = response.data;
      });
    },
    calcCellsWidth(cells){
      let count = 0;
      let y_start = null;
      cells.forEach(element => {
        if (y_start == null) y_start = element.y;
        if (y_start !== element.y) return count;
        count++;
      });
      return count;
    },
      loadMap() {
          let uri = '/map';
          axios.get(uri).then((response) => {
              this.map = response.data;
          });
      },
    loadCells() {
      let uri = '/map/cells';
      axios.get(uri).then((response) => {
        this.applyCells(response.data);
        if (this.hero){
            let cell = this.cells.find(item => item.id == this.hero.cell_id);
            this.status.cell = cell;
            this.autoScrollToUnit();
        }

      });
    },
    applyCells(cells){
      this.cells = cells;
      this.map_local_width = this.calcCellsWidth(this.cells);
      let building_cells = this.cells.filter(item => item.building_animation > 0);
      this.$nextTick(() => {
        if (building_cells) {
          building_cells.forEach(building_cell =>{
            let element = document.getElementById("cell-" + building_cell.id);
            let animation = element.animate(Animations.building, {
              duration: 1000,
              iterations: building_cell.building_animation
            });
            this.animations.push(animation);
          });

        }
      });
    },
    selectCell(cell,event){
      this.applyStatusCell(cell);
      this.menuOpen(event);
    },
    applyStatusCell(cell){
      this.status.cell = cell;
      switch (cell.object_class){
        case 'Unit':
          this.status.attribute_emoji = 'red_heart';
          break;
        case 'Building':
          this.status.attribute_emoji = 'red_heart';
          break;
      }

    },
    priorityAction(cell){
      if (cell.object_class=='Unit' && cell.object_type=='NPC'){
         this.interactionWithUnit(cell.object_id);
      }else if(cell.transfer_to_cell_id){
        this.transferToCell(cell.id,cell.transfer_to_cell_id);
      }else{
        this.moveToCell(cell.id);
      }
    },
    moveToCell(id){
      this.menuHide();
      let cell = this.cells.find(item => item.id == id);

      let uri = '/movetocell/'+id;
      axios.get(uri).then((response) => {
        if (response.data.hasOwnProperty('notify')){
          let notify = response.data.notify;
          this.showNotfy(notify.type,notify.message);
        }else{
          this.applyCells(response.data.cells);
          this.clearAnimations();
          this.hero.cell_id = id;
          this.loadHero();
          let path = response.data.path;
          this.$nextTick(() => {
            let counter = 0;
            path.forEach(path_cell => {
              counter++;
              let cell = this.cells.find(item => item.id == path_cell.id);
              if (cell && cell.object_emoji == null) {

                let element = document.getElementById("cell-" + path_cell.id);

                element.animate(Animations.hero_steps, {duration: 1000, delay: 200*counter});
              }else if (this.hero.cell_id==path_cell.id){
                let element = document.getElementById("cell-img-" + path_cell.id);

                element.animate(Animations.hero_show, {duration: 1000});
              }
            });
          });

          this.playSound('/audio/effects/'+cell.surface_type+'_step.mp3')
          this.autoScrollToUnit();
        }

      });
    },
    transferToCell(cell_id,target_cell_id){
      this.menuHide();
      let cell = this.cells.find(item => item.id == cell_id);
      this.playSound('/audio/effects/use_'+cell.emoji+'.wav')
      let uri = '/transfertocell/'+target_cell_id;

        axios.get(uri).then((response) => {
          if (response.data.success) {
              this.loadMap();
            this.applyCells(response.data.cells);
            this.$refs.events.addEvent(response.data.event);
            this.hero.cell_id = target_cell_id;
            this.loadHero();
            this.autoScrollToUnit();
          } else {
            let notify = response.data.notify;
            this.showNotfy(notify.type, notify.message);
          }

        });

    },
    scrollToElement(){
      let element = document.getElementById("cell-"+this.hero.cell_id);
      element.scrollIntoView({behavior: "smooth", block: "center",inline: "center"});
    },
    autoScrollToUnit(){
      this.$nextTick(() => {
        this.scrollToElement();
      });
    },
    open(component){
      this.menuHide();
      this.$refs[component].open();
      this.show[component] = true;
    },
    close(component){
      this.show[component] = false;
      this.loadHero();
      this.loadCells();
    },
    changeSound(){
        if (this.settings.sound){
            this.settings.sound = false;
            localStorage.sound = 'disable';
        }else{
            this.settings.sound = true;
            localStorage.sound = 'enable';
        }
    },
    closeEducation(){
      this.show["education"] = false;
      localStorage.educated = true;
    },
    readNote(note_id){
        this.menuHide();
        this.$refs.note.startRead(note_id);
        this.show.note = true;
    },
    writeNote(cell){
      this.menuHide();
      this.$refs.note.startWrite(cell.id);
      this.show.note = true;
    },
    destroyNote(cell_id,note_id){
      this.menuHide();
      axios.delete('/cell/'+cell_id+'/notes/'+note_id).then((response) => {
        this.updateCell(response.data);
      });
    },
    startDialogue(unit){
      this.menuHide();
      this.$refs.dialogue.clearQuestion();
      this.$refs.dialogue.loadQuestion(unit.question_id);
      this.$refs.dialogue.emoji = unit.emoji;
      this.$refs.dialogue.name = unit.name;
      this.show.dialogue = true;
    },
    closeDialogue(){
      this.showDialogue = false
    },
    clearAnimations(){
      this.animations.forEach((animation,index) =>{
        animation.cancel();
        this.animations.slice(index,1);
      })
    },
    takeItem(cell){
      axios.get('/cell/'+cell.id+'/take-item').then((response) => {
        this.menuHide();
        this.updateCell(response.data.cell)
      });
    },
    buildBuilding(blueprint_id){

      this.show.construction = false;
      axios.post('/buildings/create',{cell_id:this.status.cell.id,blueprint_id:blueprint_id}).then((response) => {

        this.playSound('/audio/effects/construction_building.mp3');
        let element = document.getElementById("cell-"+this.status.cell.id);
        let animation = element.animate(Animations.building, {duration: 1000, iterations:response.data.blueprint.creation_duration});
        this.animations.push(animation);
        this.loadCells();
      });
    },
    destroyBuilding(cell_id){
      this.menuHide();
      axios.delete('/buildings/destroy/'+cell_id).then((response) => {
        this.playSound('/audio/effects/destroy_building.mp3');
        let new_cell = response.data.cell;
        let cell = this.cells.find(item => item.id == cell_id);
        cell.emoji = new_cell.emoji;
        cell.name = new_cell.name;
        cell.object_class = null;
        cell.object_emoji = null;
      });
    },
    attack(cell,area){
      this.menuHide();
      let uri = '/cell/'+cell.id+'/attack/'+area;
      axios.get(uri).then((response) => {
        if (response.data.hasOwnProperty('damage')){
          this.showAttakUnit(cell.id,response.data.damage);
          this.loadHero();
        }
        if (response.data.hasOwnProperty('notify')){
          let notify = response.data.notify;
          this.showNotfy(notify.type,notify.message);
        }
      });
    },
    defence(hero_id,area){
      this.menuHide();
      let uri = '/hero/'+hero_id+'/defence/'+area;

      axios.get(uri).then((response) => {
        this.loadHero();
        this.playSound('/audio/effects/use_defence.mp3');
        let element = document.getElementById("cell-img-"+this.hero.cell_id);

        let animation = element.animate(Animations.unit_use_defence, {duration: 1000});

        if (response.data.hasOwnProperty('notify')){
          let notify = response.data.notify;
          this.showNotfy(notify.type,notify.message);
        }
      });
    },
      logout(){

      }
  }


}
</script>
