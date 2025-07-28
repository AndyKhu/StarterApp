<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Toaster } from '@/components/ui/sonner'
import 'vue-sonner/style.css'
import type { BreadcrumbItemType } from '@/types';
import PageContainer from './PageContainer.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageContainer>
            <slot />
        </PageContainer>
        <Toaster richColors/>
    </AppLayout>
</template>
