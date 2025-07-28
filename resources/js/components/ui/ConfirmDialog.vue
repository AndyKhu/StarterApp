<script setup lang="ts">
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'

const props = defineProps<{
  title?: string
  description?: string
  confirmText?: string
  cancelText?: string
  onConfirm: () => void
}>()

const open = defineModel<boolean>('open', { default: false })

const handleConfirm = () => {
  props.onConfirm()
  open.value = false
}
</script>

<template>
  <Dialog v-model:open="open">
    <DialogContent class="sm:max-w-[400px]">
      <DialogHeader>
        <DialogTitle>{{ props.title || 'Confirm Action' }}</DialogTitle>
        <p v-if="props.description" class="text-sm text-muted-foreground">{{ props.description }}</p>
      </DialogHeader>
      <DialogFooter>
        <Button variant="outline" @click="open = false">{{ props.cancelText || 'Cancel' }}</Button>
        <Button variant="destructive" @click="handleConfirm">{{ props.confirmText || 'Confirm' }}</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
