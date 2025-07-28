<template>
    <div :class="cn('relative overflow-hidden rounded-lg',file && imagePreview ? '': 'p-4 border-dashed border-2 border-muted-foreground/30 dark:border-muted')"
        @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop">
        <!-- 📸 Image Preview (replaces dropzone) -->
        <div v-if="file && imagePreview" class="relative w-full aspect-video rounded overflow-hidden border bg-muted">
            <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
            <Button variant="ghost" size="icon" class="absolute top-1 right-1 z-10 opacity-80 hover:opacity-100"
                @click.stop="removeFile">
                <XIcon class="w-4 h-4 text-destructive" />
            </Button>
        </div>

        <!-- 🧲 Dropzone -->
        <div v-else class="flex flex-col items-center justify-center gap-2 text-center cursor-pointer h-52"
            @click="triggerFileInput">
            <UploadIcon class="w-6 h-6 text-muted-foreground" />
            <p class="text-sm text-muted-foreground">
                Drag & drop an image here or click to upload
            </p>
            <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handleFileSelect" />
        </div>

        <!-- 🌀 Drag Overlay -->
        <div v-if="isDragging"
            class="absolute inset-0 bg-muted/40 border-2 border-primary border-dashed rounded-lg pointer-events-none" />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { UploadIcon, XIcon } from 'lucide-vue-next'
import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { cn } from '@/lib/utils'

const fileInput = ref<HTMLInputElement | null>(null)
const isDragging = ref(false)
const file = ref<File | null>(null)
const imagePreview = ref<string | null>(null)

const emit = defineEmits<{
    (e: 'update:file', file: File | null): void
}>()

function triggerFileInput() {
    fileInput.value?.click()
}

function handleDrop(e: DragEvent) {
    isDragging.value = false
    const droppedFile = e.dataTransfer?.files?.[0]
    if (droppedFile) setFile(droppedFile)
}

function handleFileSelect(e: Event) {
    const input = e.target as HTMLInputElement
    const selectedFile = input.files?.[0]
    if (selectedFile) setFile(selectedFile)
}

function setFile(newFile: File) {
    file.value = newFile
    emit('update:file', newFile)

    const reader = new FileReader()
    reader.onload = (e) => {
        imagePreview.value = e.target?.result as string
    }
    reader.readAsDataURL(newFile)
}

function removeFile() {
    file.value = null
    imagePreview.value = null
    emit('update:file', null)
}
</script>
