<script setup lang="ts">
import { ScrollArea } from '@/components/ui/scroll-area';

defineProps<{
    scrollable?:string;
}>();
</script>

<template>
    <ScrollArea v-if="scrollable"  class='h-[calc(100dvh-52px)]'>
        <div class='flex flex-1 p-4 md:px-6'>
            <slot/>
        </div>
    </ScrollArea>
    <div v-else class='flex flex-1 p-4 md:px-6'>
        <slot/>
    </div>
</template>