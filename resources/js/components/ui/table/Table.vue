<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class'],
  tclass?: string,
}>()
</script>

<template>
  <div data-slot="table-container" :class="cn('relative w-full overflow-auto '+props.tclass)">
    <table data-slot="table" :class="cn('w-full caption-bottom text-sm', props.class)">
      <slot />
    </table>
  </div>
</template>
