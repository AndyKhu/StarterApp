<script setup lang="ts">
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '.';
import { Input } from '../input';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectItem } from '../select';

interface Props {
    name: string;
    isDisabled?: boolean;
    placeholder?: string;
    label?: string;
    items: { id: number; name: string }[];
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
                <Select v-bind="{ ...componentField, ...$attrs }" :disabled="props.isDisabled">
                    <FormControl>
                        <SelectTrigger class="w-full">
                            <SelectValue :placeholder="props.placeholder"/>
                        </SelectTrigger>
                    </FormControl>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem v-for="item in props.items" :value="item.id" :key="item.id">
                                {{ item.name }}
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
                <FormMessage />
            </FormItem>
        </FormField>
    </div>
</template>