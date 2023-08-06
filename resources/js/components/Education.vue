<script setup>

import Modal from "./reusable/Modal.vue";
</script>

<template>
    <modal :show="show" :height="'h-screen lg:h-[80vh]'" :width="'w-screen lg:w-[40vw]'">
        <template v-slot:header>
            <div class="grid grid-cols-4 bg-yellow-100 w-full h-full">
                <div class="col-span-3 justify-self-start flex align-items-center">
                    <img class="h-10 w-10 m-auto" src="/public/open_emoji/lite_colored/hacker_cat.png">
                    <h2 class="p-3">Education</h2>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="h-full overflow-y-scroll">
                <div class="py-4 min-h-full bg-gray-200 flex w-full">
                    <div class="self-center w-full">
                        <div class="p-4 text-center f">
                            <p class="text-xl font-mono">
                                <b>Move around the map</b> - click on the cells choosing an active action.
                            </p>
                        </div>
                        <div >
                            <img  class="shadow-md rounded-md m-auto" src="/public/education/select.gif">
                        </div>
                    </div>

                </div>
                <div class="py-4 min-h-full bg-white flex w-full">
                    <div class="self-center w-full">
                        <div class="p-4 text-center f">
                            <p class="text-xl font-mono">
                              <b>Interact with the environment</b> - communicate with NPCs, pick up items, build your base.
                        </p>
                    </div>
                    <div>
                        <img class="shadow-md rounded-md m-auto" src="/public/education/interactive.gif">
                    </div>
                    </div>

                </div>
                <div class="py-4 min-h-full bg-gray-200 flex w-full">
                    <div class="self-center w-full">
                        <div class="p-4 text-center f">
                            <p class="text-xl font-mono">
                              <b>Use the interface</b> - equipment, map, quests, skills... all this will definitely come in handy and is located at the bottom of the screen!
                        </p>
                    </div>
                    <div>
                        <img class="shadow-md rounded-md m-auto w-[65%]" src="/public/education/interface.gif">
                    </div>
                    </div>

                </div>
                <div class="flex items-center place-content-center py-4">
                    <button class="bg-slate-300 active:bg-slate-600 p-2 rounded hover:bg-slate-500 hover:shadow-lg" @click="$emit('close')">Понятно</button>
                </div>
            </div>


        </template>
    </modal>
</template>

<style scoped>

</style>
