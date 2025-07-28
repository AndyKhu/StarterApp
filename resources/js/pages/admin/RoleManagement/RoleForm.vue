<script setup lang="ts">
import { computed, watch } from 'vue'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import { toast } from 'vue-sonner'
import { CirclePlus, Save } from 'lucide-vue-next'

import { Button } from '@/components/ui/button'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { FormControl, FormField, FormItem, FormLabel} from '@/components/ui/form'
import Separator from '@/components/ui/separator/Separator.vue'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Checkbox } from '@/components/ui/checkbox'
import { Switch } from '@/components/ui/switch'

import { permissionMenu } from '@/constants/menu'
import { usePermissions } from '@/composables/usePermissions'
import { PermissionAction, RoleFormData, roleSchema } from './RoleSchema'
import type { MenuPermission, Permission, Role } from '@/types'
import cloneDeep from 'lodash/cloneDeep'
import { formatErrors } from '@/utils/FormatErrors'
import { useInertiaRequest } from '@/composables/useInertiaRequest'
import BaseFormField from '@/components/ui/form/BaseFormField.vue'

/* ---------------------- helpers ---------------------- */
function applyPermissionsFromDB(
  menuList: MenuPermission[],
  dbPermissions: Permission[]
): MenuPermission[] {
  // permission name looks like: "<action> <menu>"
  const byMenu = new Map<string, Set<string>>()

  for (const { name } of dbPermissions) {
    const [action, ...rest] = name.split(' ')
    const menu = rest.join(' ')
    if (!byMenu.has(menu)) byMenu.set(menu, new Set())
    byMenu.get(menu)!.add(action)
  }

  return menuList.map((m) => {
    const actions = byMenu.get(m.menu)
    if (!actions) return { ...m }
    const clone: MenuPermission = { ...m }
    for (const action of actions) {
      if (action in clone) (clone as any)[action] = true
    }
    return clone
  })
}
const { request, processing } = useInertiaRequest()

/* ---------------------- v-models & props ---------------------- */
const dialogOpen = defineModel<boolean>('dialogOpen', { default: false })

interface Props {
    role: Role | null
    openCreate: () => void
}
const props = defineProps<Props>()

/* ---------------------- permissions ---------------------- */
const { can } = usePermissions()

/* ---------------------- constants ---------------------- */
const ACTIONS = ['view', 'create', 'edit', 'delete'] as const satisfies readonly PermissionAction[]
const BASE_MENU_PERMISSIONS: MenuPermission[] = permissionMenu()

/* ---------------------- form ---------------------- */
const defaultValues: RoleFormData = {
    name: '',
    icon: '',
    status: true,
    permissions: cloneDeep(BASE_MENU_PERMISSIONS),
}

const form = useForm<RoleFormData>({
    validationSchema: toTypedSchema(roleSchema),
    initialValues: defaultValues,
})

const { values, handleSubmit, resetForm, setValues } = form

const isEdit = computed(() => !!props.role)

/* ---------------------- helpers (form) ---------------------- */
const resetToBlank = () => {
    resetForm({
        values: {
            ...defaultValues,
            permissions: cloneDeep(BASE_MENU_PERMISSIONS), // fresh deep copy
        },
    })
}

const setFromRole = (role: Role) => {
    setValues({
        ...role,
        permissions: applyPermissionsFromDB(BASE_MENU_PERMISSIONS, role.permissions),
    })
}

/* ---------------------- submit ---------------------- */
const save = handleSubmit((payload: RoleFormData) => {
    const onSuccess = () => {
        toast.success(`Role ${isEdit.value ? 'updated' : 'created'} successfully!`, {
            description: `Role ${payload.name} has been ${isEdit.value ? 'updated' : 'created'} successfully.`,
        })
        dialogOpen.value = false
    }

    const onError = (errors: unknown) => {
        toast.error(`Failed to ${isEdit.value ? 'update' : 'create'} role!`, {
            description: `${formatErrors(errors)}`,
        })
    }

    if (props.role) {
        request('put', route('roles.update', props.role.id), payload, { onSuccess, onError })
    } else {
        request('post', route('roles.store'), payload, { onSuccess, onError })
    }
})

/* ---------------------- create button handler ---------------------- */
const onCreateClick = () => {
    resetToBlank()
    props.openCreate()
}

/* ---------------------- sync form with role ---------------------- */
watch(
    () => props.role,
    (role) => {
        if (role) setFromRole(role)
        else resetToBlank()
    },
    { immediate: true }
)
</script>

<template>
    <Button v-if="can('create Roles Management')" type="button" class="flex items-center gap-2" @click="onCreateClick">
        <CirclePlus :size="18" />
        <span>Create</span>
    </Button>

    <Dialog v-model:open="dialogOpen">
        <DialogContent class="sm:max-w-[600px]">
            <DialogHeader>
                <DialogTitle>Role Form</DialogTitle>
                <DialogDescription class="hidden">Form to create or edit a Role</DialogDescription>
            </DialogHeader>

            <Separator />

            <form id="dialogForm" @submit="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4 items-stretch">
                    <BaseFormField name="name" placeholder="e.g., Administrator" label="Name" :disabled="processing" />
                    <BaseFormField name="icon" placeholder="e.g., shield-check" label="Icon" :disabled="processing" />
                    <FormField v-slot="{ value, handleChange }" name="status">
                        <FormItem>
                            <FormLabel
                                :class="`flex flex-row items-center justify-between rounded-md bg-transparent dark:bg-input/30 border-input border p-2 ${processing ? 'pointer-events-none cursor-not-allowed opacity-50' : ''}`">
                                <span>Status</span>
                                <FormControl>
                                    <Switch :model-value="value" @update:model-value="handleChange"
                                        :disabled="processing" />
                                </FormControl>
                            </FormLabel>
                        </FormItem>
                    </FormField>
                </div>

                <div class="border rounded-md">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[40%]">Menu</TableHead>
                                <TableHead v-for="action in ACTIONS" :key="action"
                                    class="capitalize text-center w-[60px]">
                                    {{ action }}
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow v-for="(perm, index) in values.permissions" :key="perm.menu">
                                <TableCell class="font-medium">{{ perm.menu }}</TableCell>

                                <TableCell v-for="action in ACTIONS" :key="action" class="text-center">
                                    <div class="flex justify-center items-center h-full">
                                        <FormField v-slot="{ value, handleChange }" type="checkbox"
                                            :name="`permissions[${index}].${action}`">
                                            <FormItem>
                                                <FormControl>
                                                    <Checkbox :model-value="value" @update:model-value="handleChange"
                                                        :disabled="processing" />
                                                </FormControl>
                                            </FormItem>
                                        </FormField>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </form>

            <DialogFooter>
                <Button type="submit" :disabled="processing" form="dialogForm"
                    class="min-w-[120px] flex items-center gap-2">
                    <Save :size="18" />
                    <span>Save Role</span>
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
