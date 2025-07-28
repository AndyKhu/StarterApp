<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, User } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import UserTable from './UserTable.vue';
import { toast } from 'vue-sonner';
import { onMounted, ref } from 'vue';
import UserForm from './UserForm.vue';
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';
import { formatErrors } from '@/utils/FormatErrors';
const pageTitle = "Users Management"
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/',
    },
    {
        title: pageTitle,
        href: '/admin/users',
    },
]
const props = defineProps<{
    users: User[]
    roles: { id: number; name: string }[]
}>()

const selectedUser = ref<User | null>(null)
const dialogOpen = ref<boolean>(false);
const userToDelete = ref<User | null>(null)
const confirmOpen = ref<boolean>(false)

const deleteRole = () => {
    if (!userToDelete.value) return
    router.delete(route('users.delete', { id: userToDelete.value.id }), {
        onSuccess: () => {
            toast.success('Delete successfully!', {
                description: `User ${userToDelete.value?.username} has been deleted successfully.`,
            })
        },
        onError: (errors) => {
            toast.error('Failed to delete!', { description: `${formatErrors(errors)}` })
        }
    })
}
const openEdit = (user: User) => {
    selectedUser.value = { ...user }
    dialogOpen.value = true
}
const openCreate = () => {
    selectedUser.value = null
    dialogOpen.value = true
}
const confirmDelete = (user: User) => {
    userToDelete.value = user
    confirmOpen.value = true
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head :title="pageTitle" />
        <div class="flex flex-1 flex-col space-y-4 @container/main">
            <div class="flex items-center justify-between">
                <Heading :title="pageTitle" description="Manage users and role for your application" />
                <UserForm v-model:dialog-open="dialogOpen" :user="selectedUser" :open-create="openCreate"
                    :roles="roles" />
            </div>
            <UserTable :open-edit="openEdit" :delete-role="confirmDelete" :users="props.users" />
        </div>
        <ConfirmDialog v-model:open="confirmOpen" title="Delete User?"
            :description="`Are you sure you want to delete user '${userToDelete?.username}'?`"
            confirm-text="Delete" cancel-text="Cancel" :on-confirm="deleteRole" />
    </AppLayout>
</template>