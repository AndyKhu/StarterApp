<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <td
    data-slot="table-cell"
    :class="
      cn(
        'p-4 align-middle bg-input/5 dark:bg-input/30 whitespace-nowrap border-r last:border-0 border-border/60',
        props.class,
      )
    "
  >
    <slot />
  </td>
</template>
