<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { Button } from '@/components/ui/button';
import { CirclePlus, Save } from 'lucide-vue-next';
import Separator from '@/components/ui/separator/Separator.vue';
import { User } from '@/types';
import { computed, ref, watch } from 'vue';
import { usePermissions } from '@/composables/usePermissions';
import AvatarUpload from '@/components/ui/AvatarUpload.vue';
import { useForm } from 'vee-validate';
import { getUserWithProfileSchema, UserFormData } from './UserSchema';
import { toTypedSchema } from '@vee-validate/zod';
import { Switch } from '@/components/ui/switch';
import { toast } from 'vue-sonner';
import { useInertiaRequest } from '@/composables/useInertiaRequest';
import { formatErrors } from '@/utils/FormatErrors';
import BaseFormField from '@/components/ui/form/BaseFormField.vue';
import BaseFormSelect from '@/components/ui/form/BaseFormSelect.vue';
/* ---------------------- helpers ---------------------- */
const { request, processing } = useInertiaRequest()

/* ---------------------- v-models & props ---------------------- */
const dialogOpen = defineModel<boolean>('dialogOpen', { default: false })
interface Props {
    user: User | null
    roles: { id: number; name: string }[]
    openCreate: () => void
}
const props = defineProps<Props>();

/* ---------------------- permissions ---------------------- */
const { can } = usePermissions()

/* ---------------------- form ---------------------- */
const defaultValues: UserFormData = {
    username: '',
    password: '',
    status: true,
    role_id: 0,
    profile: {
        full_name: '',
        phone: '',
        address: '',
        avatar: null,
    },
}
const isEdit = computed(() => !!props.user)
const form = useForm<UserFormData>({
    validationSchema: computed(() =>
        toTypedSchema(getUserWithProfileSchema(isEdit.value))
    ),
    initialValues: defaultValues,
})
const { values, handleSubmit, resetForm, setValues, setFieldValue } = form
/* ---------------------- helpers (form) ---------------------- */
const resetToBlank = () => {
    resetForm({
        values: defaultValues,
    })
}

const setFromRole = (user: User) => {
    setValues(user)
}

/* ---------------------- submit ---------------------- */
const save = handleSubmit((payload: UserFormData) => {
    const onSuccess = () => {
        toast.success(`User ${isEdit.value ? 'updated' : 'created'} successfully!`, {
            description: `User ${payload.username} has been ${isEdit.value ? 'updated' : 'created'} successfully.`,
        })
        dialogOpen.value = false
    }

    const onError = (errors: unknown) => {
        toast.error(`Failed to ${isEdit.value ? 'update' : 'create'} user!`, {
            description: `${formatErrors(errors)}`,
        })
    }
    if (props.user) {
        request('put', route('users.update', props.user.id), payload, { onSuccess, onError })
    } else {
        request('post', route('users.store'), payload, { onSuccess, onError })
    }
})

/* ---------------------- create button handler ---------------------- */
const onCreateClick = () => {
    resetToBlank()
    props.openCreate()
}

/* ---------------------- sync form with role ---------------------- */
watch(
    () => props.user,
    (user) => {
        if (user) setFromRole(user)
        else resetToBlank()
    },
    { immediate: true }
)
</script>
<template>
    <Button v-if="can('create Users Management')" type="button" class="flex items-center gap-2" @click="onCreateClick">
        <CirclePlus :size="18" />
        <span>Create</span>
    </Button>
    <Dialog v-model:open="dialogOpen">
        <DialogContent class="sm:min-w-[600px]">
            <DialogHeader>
                <DialogTitle>Role Form</DialogTitle>
                <DialogDescription class="hidden">Form to create or edit a Role</DialogDescription>
            </DialogHeader>
            <Separator />
            <form id="dialogForm" @submit="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 items-stretch">
                    <div class="flex flex-col gap-4 py-4 items-center justify-center">
                        <AvatarUpload :value="values.profile.avatar"
                            @update:value="val => setFieldValue('profile.avatar', val)" />
                    </div>
                    <div class="grid grid-cols-1 gap-4 py-4 items-stretch">
                        <BaseFormField name="profile.full_name" label="Full Name" placeholder="e.g., Test User"
                            :disabled="processing" />
                        <BaseFormField name="username" label="Username" placeholder="e.g., User"
                            :disabled="processing" />
                        <BaseFormField name="password" label="Password" placeholder="e.g., ******" type="password"
                            :disabled="processing" />
                        <BaseFormSelect name="role_id" label="Role" :items="props.roles" placeholder="Select a role"
                            :disabled="processing" />
                        <div class="flex flex-col h-full">
                            <FormField v-slot="{ value, handleChange }" name="status">
                                <FormItem>
                                    <FormLabel>Status</FormLabel>
                                    <FormControl>
                                        <Switch :model-value="value" @update:model-value="handleChange"
                                            :disabled="processing" />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                        </div>
                    </div>
                </div>
                <Separator />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4 items-stretch">
                    <BaseFormField name="profile.phone" label="Phone" placeholder="e.g., +1234567890"
                        :disabled="processing" />
                    <BaseFormField name="profile.address" label="Address" placeholder="e.g., 123 Main St, City"
                        :disabled="processing" />
                </div>
            </form>
            <DialogFooter>
                <Button type="submit" form="dialogForm" class="min-w-[120px] flex items-center gap-2">
                    <Save :size="18" />
                    <span>Save Role</span>
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>