<template>
  <PanelItem :index="index" :field="field">
    <template #value>
      <span v-if="field.viewable && field.value">
        <Tooltip
          v-if="field.peekable && field.hasFieldsToPeekAt"
          :triggers="['hover']"
          placement="top-start"
          theme="plain"
        >
          <Link
            @click.stop
            :href="
              $url(`/resources/${field.resourceName}/${field.belongsToId}`)
            "
            class="link-default"
          >
            {{ field.value }}
          </Link>

          <template #content>
            <RelationPeek
              :resource-name="field.resourceName"
              :resource-id="field.belongsToId"
            />
          </template>
        </Tooltip>

        <Link
          v-else
          :href="$url(`/resources/${field.resourceName}/${field.belongsToId}`)"
          class="link-default"
        >
          {{ field.value }}
        </Link>
      </span>
      <p v-else-if="field.value">{{ field.value }}</p>
      <p v-else>&mdash;</p>
    </template>
  </PanelItem>
</template>

<script>
export default {
  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
}
</script>
