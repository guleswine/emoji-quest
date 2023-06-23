<script>

import Modal from './reusable/Modal.vue'
export default {
  components: {
    Modal
  },
  props: {
    show: Boolean,

  },
  data() {
    return {
      cells: [],
      hero: null,
      map: {width:11},
    };
  },
  methods: {
    playSound (sound) {
      if(sound) {
        var audio = new Audio(sound);
        audio.play();
      }
    },
    open(){
      this.playSound('/audio/effects/open_global_map.wav');
      this.loadHero();
      this.getCells();
    },
    loadHero(){
      let uri = '/hero';
      axios.get(uri).then((response) => {
        this.hero = response.data;
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
    scrollToHome(){
      this.$nextTick(() => {
        let element = document.getElementById("global-cell-"+this.hero.cell_id);
        element.scrollIntoView({behavior: "smooth", block: "center",inline: "center"});
      });
    },
    getCells(){
      let uri = '/map/global-cells';
      axios.get(uri).then((response) => {
        this.cells = response.data;
        this.map.width = this.calcCellsWidth(this.cells);
        this.scrollToHome();
      });

    },
  },
}
</script>

<template>
  <modal :show="show">
    <template v-slot:header>
      <div class="grid grid-cols-4 bg-yellow-100 w-full h-full">
        <div class="col-span-3 justify-self-start flex align-items-center">
          <img class="h-10 w-10 m-auto" src="/public/open_emoji/lite_colored/openstreetmap.png">
          <h2 class="p-3">Глобальная карта</h2>
        </div>
        <div class="text-right p-3">
          <button class="hover:shadow-lg" @click="$emit('close')"><img class="h-6 w-6 m-auto" src="/public/open_emoji/lite_colored/cross_mark.png"></button>
        </div>
      </div>
    </template>
    <template v-slot:body>
      <div class="relative h-full">
        <div
            class="absolute right-4 bg-slate-200 rounded-full shadow-md"
            @click="scrollToHome()"
        >
          <img class="h-10 w-10" src="/public/open_emoji/lite_colored/north.png">
        </div>
        <div id="map-border" class="h-full overflow-scroll ">

          <div id="map" class="m-auto flex flex-wrap  " style="line-height: 0px;" :style="'width: '+(2.5*map.width)+'rem;'">
            <div :id="'global-cell-'+cell.id"
                 class="h-10 w-10 inline-flex select-none bg-transparent hover:bg-gray-400 focus:bg-gray-400 text-gray-800
                   font-semibold hover:text-white   hover:border-transparent rounded
                    bg-no-repeat bg-center"
                 :style="'background-size: '+cell.size/5+'rem; background-image: url(/open_emoji/lite_colored/'+cell.emoji+'.png);'"
                 v-for="cell in cells" >
            </div>
          </div>
        </div>
      </div>

    </template>
  </modal>

</template>

