<script setup lang="ts">
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList, BreadcrumbPage, BreadcrumbSeparator } from '@/components/ui/breadcrumb';
import { Link } from '@inertiajs/vue3';
import { useSidebar } from './ui/sidebar';

interface BreadcrumbItemType {
    title: string;
    href?: string;
}

defineProps<{
    breadcrumbs: BreadcrumbItemType[];
}>();


const { isMobile } = useSidebar();
</script>

<template>
    <Breadcrumb>
        <BreadcrumbItem v-if="isMobile"><BreadcrumbPage>{{ breadcrumbs[breadcrumbs.length-1].title }}</BreadcrumbPage></BreadcrumbItem>
        <BreadcrumbList v-else>
            <template v-for="(item, index) in breadcrumbs" :key="index">
                <BreadcrumbItem>
                    <template v-if="index === breadcrumbs.length - 1">
                        <BreadcrumbPage>{{ item.title }}</BreadcrumbPage>
                    </template>
                    <template v-else>
                        <BreadcrumbLink as-child>
                            <Link :href="item.href ?? '#'">{{ item.title }}</Link>
                        </BreadcrumbLink>
                    </template>
                </BreadcrumbItem>
                <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1" />
            </template>
        </BreadcrumbList>
    </Breadcrumb>
</template>
