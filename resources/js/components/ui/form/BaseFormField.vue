<script setup lang="ts">
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '.';
import { Input } from '../input';

interface Props {
    name: string;
    type?: string;
    isDisabled?: boolean;
    placeholder?: string;
    label?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    isDisabled: false,
    placeholder: '',
    label: '',
});

defineOptions({
    inheritAttrs: false,
});
</script>

<template>
    <div class="flex flex-col h-full">
        <FormField v-slot="{ componentField }" :name="props.name">
            <FormItem>
                <FormLabel v-if="props.label">{{ props.label }}</FormLabel>
                <FormControl>
                    <Input :type="props.type" :placeholder="props.placeholder" :disabled="props.isDisabled"
                        v-bind="{ ...componentField, ...$attrs }" />
                </FormControl>
                <FormMessage />
            </FormItem>
        </FormField>
    </div>
</template>