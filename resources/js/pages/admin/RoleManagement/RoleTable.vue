<script setup lang="ts">
import { Role } from '@/types';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Circle, CircleArrowOutUpRightIcon, CircleMinusIcon, CircleSmall, Users } from 'lucide-vue-next'
import Icon from '@/components/Icon.vue'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { usePermissions } from '@/composables/usePermissions';
import { cn } from '@/lib/utils';

interface Props {
    roles: Role[]
    deleteRole: (role: Role) => void;
    openEdit: (role: Role) => void;
}
const props = defineProps<Props>();
const { can } = usePermissions()
</script>
<template>
    <div class="border-y border-border/60">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>
                        Role
                    </TableHead>
                    <TableHead>
                        <div class="flex items-center gap-1">
                            <Icon name="Users" class="h-5 w-5" />
                            <span class="mt-1">Users</span>
                        </div>
                    </TableHead>
                    <TableHead class="w-[150px]">
                        <div class="flex items-center gap-1 justify-center">
                            <Icon name="CircleFadingPlus" class="h-5 w-5" />
                            <span class="mt-1">Status</span>
                        </div>
                    </TableHead>
                    <TableHead class="w-[100px] text-center" v-if="can('edit Roles Management') || can('delete Roles Management')">
                        <div class="flex items-center gap-1">
                            <Icon name="boxes" class="h-5 w-5" />
                            <span class="mt-1">Action</span>
                        </div>
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="role in props.roles" :key="role.id">
                    <TableCell>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-muted">
                                <Icon :name="role.icon" class="h-5 w-5" />
                            </div>
                            <div>
                                <div class="font-medium capitalize">{{ role.name }}</div>
                                <div class="text-sm text-muted-foreground">Role ID: {{ role.id }}</div>
                            </div>
                        </div>
                    </TableCell>
                    <TableCell>
                        <div class="flex items-center space-x-2">
                            <Users class="w-5 h-5" />
                            <span>{{ role.users.length }} users</span>
                        </div>
                    </TableCell>
                    <TableCell>
                        <div class="flex items-center space-x-2 justify-center">
                            <Badge variant="outline"
                                :class="cn(role.status ? 'border-green-300 text-green-600 dark:border-green-400' : 'border-red-300 text-red-600 dark:border-red-400', 'px-2 py-1 w-[80px] justify-start')">
                                <CircleSmall :class="role.status ? 'fill-green-600' : 'fill-red-600'"></CircleSmall>
                                {{ role.status ? "Active" : "Inactive" }}
                            </Badge>
                        </div>
                    </TableCell>
                    <TableCell v-if="can('edit Roles Management') || can('delete Roles Management')">
                        <div class="flex items-center justify-center space-x-2">
                            <Button size="icon" variant="success" @click="openEdit(role)"
                                v-if="can('edit Roles Management')">
                                <CircleArrowOutUpRightIcon />
                            </Button>
                            <Button size="icon" variant="destructive" @Click="deleteRole(role)"
                                v-if="can('delete Roles Management')">
                                <CircleMinusIcon />
                            </Button>
                        </div>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>