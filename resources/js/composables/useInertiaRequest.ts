// composables/useInertiaRequest.ts
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

type HTTPMethod = 'get' | 'post' | 'put' | 'patch' | 'delete'

/** Recursively checks if any value is a File or Blob */
function hasFile(value: any): boolean {
  if (!value) return false
  if (value instanceof File || value instanceof Blob) return true
  if (Array.isArray(value)) return value.some(hasFile)
  if (typeof value === 'object') return Object.values(value).some(hasFile)
  return false
}

export function useInertiaRequest() {
  const processing = ref(false)

  function request(
    method: HTTPMethod,
    url: string,
    data?: any,
    options: Record<string, any> = {}
  ) {
    processing.value = true

    const containsFile = hasFile(data)
    const shouldSpoof =
      containsFile && method !== 'post' && method !== 'get'

    // If there is a file and method is PUT/PATCH/DELETE, send POST + _method
    const visitMethod: HTTPMethod = shouldSpoof ? 'post' : method
    const payload = shouldSpoof
      ? { ...(data || {}), _method: method.toUpperCase() }
      : data

    const mergedOptions = {
      ...options,
      forceFormData: containsFile || options.forceFormData === true,
      onFinish: () => {
        processing.value = false
        options?.onFinish?.()
      },
    }

    const r: any = router

    // Handle different Inertia signatures safely
    if (visitMethod === 'get') {
      // get(url, data?, options?)
      return r.get(url, payload || {}, mergedOptions)
    }

    if (visitMethod === 'delete') {
      // delete(url, options?)
      return r.delete(url, mergedOptions)
    }

    // post / put / patch -> (url, data, options)
    return r[visitMethod](url, payload, mergedOptions)
  }

  return { request, processing }
}
