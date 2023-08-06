<script>
export default {
  props: {
    show: Boolean,

  },
  data() {
    return {
      skills: [],
      hero: null,
      skill: null
    };
  },
  methods: {
    open(){
      this.loadSkills();
    },
    loadSkills(){
      let uri = '/skills';
      axios.get(uri).then((response) => {
        this.skills = response.data.skills;
        this.hero = response.data.hero;
        this.$nextTick(() => {
          this.scrollOnStart();
        });
      });

    },
    scrollOnStart(){
      document.getElementById('object-1').scrollIntoView({
        behavior: 'auto',
        block: 'center',
        inline: 'center'
      });
    },
    menuOpen(event,skill){
        if (!this.show) return;
      let screen_width = window.screen.width;
      let posX = event.clientX-30;
      let posY = event.clientY+20
      //event.stopPropagation();
      this.skill = skill;
      if (screen_width<(event.clientX+200)){
        posX=screen_width-200;
      }else{

      }
      let contextMenuOpen = document.getElementById("skill-menu");
      contextMenuOpen.style.left = posX + 'px';
      contextMenuOpen.style.top = posY + 'px';
      contextMenuOpen.style.display = 'block';
    },
    close(){

      this.$emit('close');
      this.menuHide();
    },
    menuHide(){
      let contextMenuOpen =  document.getElementById("skill-menu");
      contextMenuOpen.style.display = 'none';
    },
    learnSkill(skill){
      if (skill.unlocked && skill.learned==false){
        let uri = '/skill/' + skill.id + '/learn';
        axios.get(uri).then((response) => {
          this.loadSkills();
          this.menuHide();
        });
      }
    }
  },
}
</script>

<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container shadow bg-white mx-auto w-screen h-screen lg:h-[90vh]  lg:w-[815px]">
          <div class="h-[6vh] lg:h-[5vh]  w-full bg-slate-100 grid grid-cols-4">
            <div class="col-span-3 justify-self-start flex align-items-center">
              <img class="h-10 w-10 m-auto" src="/public/open_emoji/lite_colored/books.png">
              <h2 class="p-3 align-self-center">Skills</h2>
            </div>
            <div class="text-right p-3">
              <button class="hover:shadow-lg" @click="close"><img class="h-6 w-6 m-auto" src="/public/open_emoji/lite_colored/cross_mark.png"></button>
            </div>
          </div>

          <div id="skills-region" class="modal-body grid grid-cols-2 h-[84vh] lg:h-[95%] w-full overflow-scroll bg-gray-200">
            <!-- Окно навыков -->

            <svg width="800" class="h-full"  >
              <foreignObject  height="60" width="180" x="180" y="20" class="text-center">
                <p class="font-semibold col-span-2 text-center">Free points:</p>
                <p v-if="hero">{{hero.skill_points}}</p>
              </foreignObject>
                <foreignObject :id="'object-'+skill.id" class="text-center" height="64" width="64" :x="370-((skill.row-1)*35)+((skill.col-1)*70)" :y="((skill.row-1)*65)+10" v-for="skill in skills" >

                  <button class="w-[3.75rem] h-[3.75rem] border-2 border-slate-600 rounded-full  shadow-md hover:shadow-lg overflow-hidden
                         text-white"
                          :class="{ 'bg-blue-300': (skill.learned === true), 'bg-gray-100':(skill.unlocked === true && skill.learned !== true), 'bg-gray-400': !skill.unlocked && !skill.learned,
                          'hover:bg-blue-400': (skill.learned === true), 'hover:bg-gray-300':(skill.unlocked === true && skill.learned !== true), 'hover:bg-gray-500': !skill.unlocked && !skill.learned}"
                          @mouseenter="menuOpen($event,skill)" @mouseleave="menuHide" @dblclick="learnSkill(skill)">
                    <img class="h-14 w-14 m-auto"  style="max-width: none;" v-bind:src="'/open_emoji/lite_colored/'+skill.emoji+'.png'">
                  </button>
                </foreignObject>
              </svg>
          </div>

        </div>
      </div>
    </div>
  </Transition>
  <div  id="skill-menu" class="context-menu-open text-center w-48">
    <h3 v-if="skill" class="py-2 font-semibold ">{{skill.label}}</h3>
    <p v-if="skill" class="italic ">{{skill.description}}</p>

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
