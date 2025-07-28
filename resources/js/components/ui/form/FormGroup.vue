<script setup lang="ts">
import { FormField } from '@/components/ui/form'
import FormItem from './FormItem.vue';

defineOptions({ name: 'FromGroup' })

const props = defineProps<{
  name: string
  class?: string
}>()
</script>

<template>
  <div class="flex flex-col gap-3" :class="props.class">
    <FormField :name="props.name">
      <FormItem>
        <slot />
      </FormItem>
    </FormField>
  </div>
</template>
