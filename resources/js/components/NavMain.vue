<script setup lang="ts">
import { ChevronRight } from 'lucide-vue-next'
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from '@/components/ui/collapsible'
import {
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarMenuSub,
  SidebarMenuSubButton,
  SidebarMenuSubItem,
} from '@/components/ui/sidebar'
import { NavItem, type GroupNavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';
import { computed } from 'vue';

const props = defineProps<{
  items: GroupNavItem[];
}>();
const { can } = usePermissions()
const page = usePage();

const filteredItems = computed<GroupNavItem[]>(() =>
  props.items
    .map(group => {
      const filteredGroupItems = (group.items ?? [])
        .map((item: NavItem) => {
          const subItems = (item.items ?? []).filter(sub => can(`view ${sub.title}`))
          if (!item.items?.length) {
            return can(`view ${item.title}`) ? item : null
          }

          return subItems.length
            ? { ...item, items: subItems }
            : null
        })
        .filter(Boolean)

      return filteredGroupItems.length ? { ...group, items: filteredGroupItems } : null
    })
    .filter(Boolean) as GroupNavItem[]
)



const isGroupActive = (item: GroupNavItem['items'][number]): boolean => {
  return item.items?.some((subItem) => subItem.href === page.url) ?? false;
};
</script>

<template>
  <SidebarGroup v-for="menuitem in filteredItems" :key="menuitem.title">
    <SidebarGroupLabel>{{menuitem.title}}</SidebarGroupLabel>
    <SidebarMenu>
      <template v-for="item in menuitem.items" :key="item.title">
        <Collapsible v-if="item.items && item.items?.length > 0" as-child :default-open="isGroupActive(item)" class="group/collapsible">
          <SidebarMenuItem>
            <CollapsibleTrigger as-child>
              <SidebarMenuButton :tooltip="item.title">
                <component :is="item.icon" v-if="item.icon" />
                <span>{{ item.title }}</span>
                <ChevronRight
                  class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
              </SidebarMenuButton>
            </CollapsibleTrigger>
            <CollapsibleContent>
              <SidebarMenuSub>
                <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.title" :is-active="subItem.href === page.url">
                  <SidebarMenuSubButton as-child >
                    <Link :href="subItem.href">
                    <span>{{ subItem.title }}</span>
                    </Link>
                  </SidebarMenuSubButton>
                </SidebarMenuSubItem>
              </SidebarMenuSub>
            </CollapsibleContent>
          </SidebarMenuItem>
        </Collapsible>
        <SidebarMenuItem v-else>
          <SidebarMenuButton as-child :is-active="item.href === page.url" :tooltip="item.title">
            <Link :href="item.href">
            <component :is="item.icon" />
            <span>{{ item.title }}</span>
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </template>
    </SidebarMenu>
  </SidebarGroup>
</template>
