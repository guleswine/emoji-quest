<script>

export default {

    props: {
        emoji: String

    }
}
</script>
<template>
    <li class="flex" >
        <img v-if="emoji" class="h-6 w-6"  :src="'/open_emoji/lite_colored/'+emoji+'.png'">
        <p>
            <slot>
            </slot>
        </p>
    </li>
</template>