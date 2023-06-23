<script>

export default {

  props: {
    emoji: String,

  }
}
</script>
<template>
<button
    class="inline-block bg-slate-300 active:bg-slate-600 m-1
          hover:bg-slate-500 text-white font-medium
           text-xs leading-tight uppercase rounded
            shadow-md hover:shadow-lg active:shadow-lg
             transition duration-150 ease-in-out"
id="show-modal" >
<img class="h-12 w-12 m-auto"  style="max-width: none;" :src="'/open_emoji/lite_colored/'+emoji+'.png'">
</button>
</template>