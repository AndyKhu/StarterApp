<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';
import { computed } from 'vue';

interface Props {
    user: User;
}

const props = withDefaults(defineProps<Props>(), {
});

const { getInitials } = useInitials();

// Compute whether we should show the avatar image
const showAvatar = computed(() => props.user.profile.avatar && props.user.profile.avatar !== '');
// Replace with your actual logic to generate the avatar URL, for example:
const avatarURL = computed(() => props.user.profile.avatar ? `/storage/${props.user.profile.avatar}` : '');
</script>

<template>
    <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
        <AvatarImage v-if="showAvatar" :src="avatarURL" :alt="user.profile.full_name" />
        <AvatarFallback class="rounded-lg text-black dark:text-white">
            {{ getInitials(user.profile.full_name) }}
        </AvatarFallback>
    </Avatar>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium">{{ user.profile.full_name }}</span>
    </div>
</template>
