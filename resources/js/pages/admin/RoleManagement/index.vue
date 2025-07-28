<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Role } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import RoleForm from './RoleForm.vue';
import { toast } from 'vue-sonner';
import RoleTable from './RoleTable.vue';
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';
const pageTitle = "Roles Management"
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/',
    },
    {
        title: pageTitle,
        href: '/admin/role',
    },
]
const props = defineProps<{
    roles: Role[]
}>()
const selectedRole = ref<Role | null>(null)
const roleToDelete = ref<Role | null>(null)
const confirmOpen = ref<boolean>(false)
const isRoleDialogOpen = ref<boolean>(false);
const deleteRole = () => {
  if (!roleToDelete.value) return
  router.delete(route('roles.delete', { id: roleToDelete.value.id }), {
    onSuccess: () => {
      toast.success('Delete successfully!', {
        description: `User ${roleToDelete.value?.name} has been deleted successfully.`,
      })
    },
    onError: (errors) => {
      toast.error('Failed to delete!', { description: `${errors}` })
    }
  })
}
const openEdit = (role: Role) => {
    selectedRole.value = { ...role }
    isRoleDialogOpen.value = true
}
const openCreate = () => {
    selectedRole.value = null
    isRoleDialogOpen.value = true
}
const confirmDelete = (role: Role) => {
  roleToDelete.value = role
  confirmOpen.value = true
}
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head :title="pageTitle" />
        <div class="flex flex-1 flex-col space-y-4 @container/main">
            <div class="flex items-center justify-between">
                <Heading :title="pageTitle" description="Manage roles and permission for your application" />
                <RoleForm v-model:dialogOpen="isRoleDialogOpen" :role="selectedRole" :openCreate="openCreate" />
            </div>
            <RoleTable :open-edit="openEdit" :delete-role="confirmDelete" :roles="props.roles" />
        </div>
        <ConfirmDialog v-model:open="confirmOpen" title="Delete Role?"
            :description="`Are you sure you want to delete role '${roleToDelete?.name}'?`"
            confirm-text="Delete" cancel-text="Cancel" :on-confirm="deleteRole" />
    </AppLayout>
</template>