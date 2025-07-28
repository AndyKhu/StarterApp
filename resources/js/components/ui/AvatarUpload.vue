<template>
  <div class="flex flex-col items-center justify-center space-y-4">
    <!-- Dropzone when no file -->
    <label
      v-if="!previewUrl"
      class="flex items-center justify-center border-2 border-dashed dark:border-gray-500 rounded-full w-40 h-40 cursor-pointer overflow-hidden"
    >
      <input
        type="file"
        accept="image/*"
        @change="onFileChange"
        class="hidden"
        ref="fileInput"
      />
      <span class="text-xs text-gray-500 text-center">Click to upload</span>
    </label>

    <!-- Preview when file is present -->
    <div v-else class="relative w-40 h-40 rounded-full overflow-hidden border-4 border-gray-300 group">
      <img :src="previewUrl" class="w-full h-full object-cover" />

      <!-- Overlay for editing -->
      <label
        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-sm cursor-pointer transition"
      >
        <input
          type="file"
          accept="image/*"
          @change="onFileChange"
          class="hidden"
          ref="fileInput"
        />
        Edit
      </label>
    </div>
    <Button v-if="previewUrl" 
        @click.prevent="removeImage" variant="destructive">Remove Image</Button>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { Button } from './button';

const props = defineProps<{
  value: File | string | null
  initialUrl?: string
}>()
const emit = defineEmits<{
  (e: 'update:value', value: File | null): void
}>()

const fileInput = ref<HTMLInputElement | null>(null)
const previewUrl = ref<string | null>(
  props.initialUrl ?? (typeof props.value === 'string' ? props.value : null)
)

watch(
  () => props.value,
  (newVal) => {
    if (newVal instanceof File) {
      previewUrl.value = URL.createObjectURL(newVal)
    } else if (typeof newVal === 'string') {
      previewUrl.value = newVal
    } else {
      previewUrl.value = null
    }
  }
)

function onFileChange(event: Event) {
  const files = (event.target as HTMLInputElement).files
  if (files?.[0]) {
    emit('update:value', files[0])
  }
}

function removeImage() {
  emit('update:value', null)
  previewUrl.value = null
}
</script>
