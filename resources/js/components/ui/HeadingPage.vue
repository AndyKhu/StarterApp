<script setup lang="ts">
defineProps<{
    title: string;
    description?:string;
}>();
</script>

<template>
    <div>
      <h2 className='text-xl md:text-3xl font-bold tracking-tight'>{{title}}</h2>
      <p v-if="description" className='text-muted-foreground text-xs md:text-sm'>{{description}}</p>
    </div>
</template>