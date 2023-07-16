<script>

export default {

  props: {
    show: Boolean,

  }
}
</script>
<template>
  <div
      class="absolute flex top-0 h-screen  pt-24 right-0 flex-row"
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
          <h3 class="py-2 pl-4 font-semibold ">Справка</h3>
        </div>
        <div class="body p-4 overflow-y-scroll h-[95%]">
          <div class="space-y-5 ">
            <div class="p-3 bg-slate-100 shadow-lg rounded-lg">
              <h3 class=" font-semibold border-b border-gray-400">Управление</h3>
              <p class="text-xs font-mono">
                <b>Клик по полю</b> - показывает доступные действия.<br>
                <b>Двойной клик</b> - запускает приоритетное активное действие.
              </p>
            </div>
            <div class="p-3 bg-slate-100 shadow-lg rounded-lg">
              <h3 class=" font-semibold border-b border-gray-400">Нижнее меню</h3>
              <p class="text-xs font-mono">
                <b>Компасс</b> - находит вашего героя на карте.<br>
                <b>Рюкзак</b> - окно экипировки и инвентаря вашего героя.<br>
                <b>Глобальная карта</b> - отрисовывает больше клеток карты.<br>
                <b>Навыки</b> - древо доступых навыков.<br>
                <b>Квесты</b> - список взятых квестов.<br>
                <b>Таланты</b> - список умений прокачивающихся автоматически.<br>
              </p>
            </div>
            <div class="p-3 bg-slate-100 shadow-lg rounded-lg">
              <h3 class=" font-semibold border-b border-gray-400">Бой</h3>
              <p class="text-xs font-mono">
                <b>Атака в голову</b> - основной урон +25%<br>
                <b>Атака в туловище</b> - обычный урон<br>
                <b>Атака в конечности</b> - 75% от основого урона и -1 очко действия на следующий ход<br>
                <b>Защита</b> - при атаке в защищенную часть, пропускается лишь 50% основого урона и не применяются дополнительные эффекты или множители, установка защиты немедленно завершает ход<br>
                <b>Рассчет урона</b> - из атаки противника вычитается ваш показатель защиты<br>
                <b>Расход очков</b> - 1 очко действия тратится на перемещение, 2 очка действия на атаку и 1 очко действия на установку защиты (которая сразу завершает ход)
              </p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>
