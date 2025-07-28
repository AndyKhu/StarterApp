<script setup lang="ts">
import { Role, User } from '@/types';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { CircleArrowOutUpRightIcon, CircleMinusIcon, CircleSmall } from 'lucide-vue-next'
import Icon from '@/components/Icon.vue'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { usePermissions } from '@/composables/usePermissions';
import { onMounted } from 'vue';
import { cn } from '@/lib/utils';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';

interface Props {
    users: User[]
    deleteRole: (user: User) => void;
    openEdit: (user: User) => void;
}
const props = defineProps<Props>();
const { can } = usePermissions()
const { getInitials } = useInitials();
</script>
<template>
    <div class="border-y border-border/60 overflow-x-auto">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>
                        Full Name
                    </TableHead>
                    <TableHead>
                        <div class="flex items-center gap-1">
                            <Icon name="user" class="h-5 w-5" />
                            <span class="mt-1">Username</span>
                        </div>
                    </TableHead>
                    <TableHead>
                        <div class="flex items-center gap-1">
                            <Icon name="UserRoundCog" class="h-5 w-5" />
                            <span class="mt-1">User Role</span>
                        </div>
                    </TableHead>
                    <TableHead class="md:w-[150px]">
                        <div class="flex items-center gap-1 justify-center">
                            <Icon name="CircleFadingPlus" class="h-5 w-5" />
                            <span class="mt-1">Status</span>
                        </div>
                    </TableHead>
                    <TableHead class="md:w-[100px] text-center" v-if="can('edit Users Management') || can('delete Users Management')">
                        <div class="flex items-center gap-1">
                            <Icon name="boxes" class="h-5 w-5" />
                            <span class="mt-1">Action</span>
                        </div>
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="user in props.users" :key="user.id">
                    <TableCell>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-muted">
                                <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
                                    <AvatarImage v-if="user.profile.avatar" :src="user.profile.avatar" :alt="user.profile.full_name" />
                                    <AvatarFallback class="rounded-lg text-black dark:text-white">
                                        {{ getInitials(user.profile.full_name) }}
                                    </AvatarFallback>
                                </Avatar>
                                <!-- <Icon :name="user.avatar" class="h-5 w-5" /> -->
                            </div>
                            <div>
                                <div class="font-medium capitalize">{{ user.profile.full_name }}</div>
                                <div class="text-sm text-muted-foreground">User ID: {{ user.id }}</div>
                            </div>
                        </div>
                    </TableCell>
                    <TableCell>
                        <div class="flex items-center space-x-2">
                            {{ user.username }}
                        </div>
                    </TableCell>
                    <TableCell>
                        <div class="flex items-center space-x-1.5" v-if="user.role">
                            <span class="capitalize">
                                {{ user.role.name }}
                            </span>
                        </div>
                        <span v-else>-</span>
                    </TableCell>
                    <TableCell>
                        <div class="flex items-center space-x-2 justify-center">
                            <Badge variant="outline"
                                :class="cn(user.status ? 'border-green-300 text-green-600 dark:border-green-400' : 'border-red-300 text-red-600 dark:border-red-400', 'px-2 py-1 w-[80px] justify-start')">
                                <CircleSmall :class="user.status ? 'fill-green-600' : 'fill-red-600'"></CircleSmall>
                                {{ user.status ? "Active" : "Inactive" }}
                            </Badge>
                        </div>
                    </TableCell>
                    <TableCell v-if="can('edit Users Management') || can('delete Users Management')">
                        <div class="flex items-center justify-center space-x-2">
                            <Button size="icon" variant="success" @click="openEdit(user)"
                                v-if="can('edit Users Management')">
                                <CircleArrowOutUpRightIcon />
                            </Button>
                            <Button size="icon" variant="destructive" @Click="deleteRole(user)"
                                v-if="can('delete Users Management')">
                                <CircleMinusIcon />
                            </Button>
                        </div>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>