<script>

export default {

  props: {
    show: Boolean,

  }
}
</script>
<template>
  <div
      class="absolute flex top-0 h-screen  pt-28 right-0 flex-row"
  >
    <!--Drawer -->


    <!-- Sidebar Content -->
    <div
        ref="content"
        class="transition-all duration-700 z-20 2xl:w-80 w-[49vw]   overflow-hidden flex items-center justify-center 2xl:max-w-lg shadow-lg"
        :class="[show ? 'max-w-lg' : 'max-w-0']"
    >
      <div class="bg-white h-full w-full overflow-hidden shadow-sm">
        <div class="header h-10 bg-slate-100 hidden 2xl:block">
          <h3 class="py-2 pl-4 font-semibold ">Help</h3>
        </div>
        <div class="body p-4 overflow-y-scroll h-[95%]">
          <div class="space-y-5 ">
            <div class="p-3 bg-slate-100 shadow-lg rounded-lg">
              <h3 class=" font-semibold border-b border-gray-400">Control</h3>
              <p class="text-xs font-mono">
                <b>Click on the field</b> - shows available actions.<br>
                <b>Double click</b> - runs the priority active action.
              </p>
            </div>
            <div class="p-3 bg-slate-100 shadow-lg rounded-lg">
              <h3 class=" font-semibold border-b border-gray-400">Lower menu</h3>
              <p class="text-xs font-mono">
                <b>Compass</b> - finds your hero on the map.<br>
                <b>Backpack</b> - your hero's equipment and inventory window.<br>
                <b>Global map</b> - draws more map cells.<br>
                <b>Skills</b> - a tree of available skills.<br>
                <b>Quests</b> - list of accepted quests.<br>
                <b>Talents</b> - a list of skills that are pumped automatically.<br>
              </p>
            </div>
            <div class="p-3 bg-slate-100 shadow-lg rounded-lg">
              <h3 class=" font-semibold border-b border-gray-400">Fight</h3>
              <p class="text-xs font-mono">
                <b>Head attack</b> - main damage +25%<br>
                <b>Attack body</b> - normal damage<br>
                <b>Limb Attack</b> - 75% of base damage and -1 action point on next turn<br>
                <b>Protection</b> - when attacking a protected part, only 50% of the main damage is skipped and additional effects or multipliers are not applied, setting protection immediately ends the turn<br>
                <b>Damage calculation</b> - your defense value is subtracted from the enemy attack<br>
                <b>Spending Points</b> - 1 action point spent on movement, 2 action points on attack, and 1 action point on defense (which immediately ends the turn)
              </p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>
