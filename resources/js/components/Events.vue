<script>

export default {

  props: {
    show: Boolean,

  },
  data(){
    return {
      events: [],
    }
  },
  methods: {
    loadEvents(){
      let uri = '/events';
      axios.get(uri).then((response) => {
        this.events = response.data;
      });
    },
    addEvent(event){
      this.events.unshift(event);
    }
  }
}
</script>
<template>
  <div
      class="absolute flex top-0 h-screen  pt-28 left-0 flex-row-reverse"
  >
    <!--Drawer -->


    <!-- Sidebar Content -->
    <div
        ref="content"
        class="transition-all z-20 2xl:w-80 w-[49vw] duration-700  overflow-hidden flex items-center justify-center 2xl:max-w-lg shadow-lg"
        :class="[show ? 'max-w-lg' : 'max-w-0']"
    >
      <div class="bg-white h-full w-full overflow-hidden shadow-sm">
        <div class="header h-10 bg-slate-100 hidden 2xl:grid grid grid-cols-2">
          <div class="col-span-1"><h3 class="py-2 pl-4 font-semibold ">Events</h3></div>
          <div class="col-span-1 py-1 text-right">
            <button class=" p-2 text-xs rounded hover:shadow-lg hover:underline " @click="loadEvents()">
              Load history</button></div>
        </div>
        <div class="body h-full">
          <div id="events-list" class="overflow-y-scroll h-[95%]">
            <ul class="px-2 py-1">
              <p v-if="events.length == 0">Here will be the history of your events</p>
              <li class="inline-flex border-b-2" v-for="event in events"><img class="h-6 w-6 m-auto"  style="max-width: none;" :src="'/open_emoji/lite_colored/'+event.emoji+'.png'">
                <p>{{ event.message }}</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
