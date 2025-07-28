<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="space-y-0.5">
        <h2 class="text-xl font-semibold tracking-tight">{{ title }}</h2>
        <p v-if="description" class="text-sm text-muted-foreground hidden sm:block">
            {{ description }}
        </p>
    </div>
</template>
