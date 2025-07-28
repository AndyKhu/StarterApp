<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class'],
  isActive?: boolean
}>()
</script>

<template>
  <li
    data-slot="sidebar-menu-sub-item"
    data-sidebar="menu-sub-item"
    :data-active="isActive"
    :class="cn('group/menu-sub-item relative',
    'before:h-2 before:w-2 before:rounded-full before:absolute before:-left-2.5 before:top-1/2 before:-translate-1/2 before:bg-sidebar-sub-accent data-[active=true]:before:bg-primary', 
    props.class)"
  >
    <slot />
  </li>
</template>
