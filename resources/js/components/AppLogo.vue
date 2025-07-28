<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded bg-primary text-sidebar-primary-foreground dark:group-data-[state=collapsed]:bg-sidebar-accent group-data-[state=collapsed]:bg-sidebar-primary">
        <AppLogoIcon class="size-5 fill-current text-white" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold">Toko Emas</span>
    </div>
</template>
