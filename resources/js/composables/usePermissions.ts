import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function usePermissions() {
  const permissions = computed(() => usePage().props.auth.permissions as string[])

  const can = (perm: string) => permissions.value.includes(perm)

  return { permissions, can }
}
