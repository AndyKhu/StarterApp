// utils/formatErrors.ts
export type InertiaErrors = Record<string, string | string[]>;

export function formatErrors(errors: unknown): string {
  if (typeof errors === 'string') return errors;
  if (Array.isArray(errors)) return errors.join('\n');

  if (errors && typeof errors === 'object') {
    return Object.entries(errors as InertiaErrors)
      .flatMap(([field, msgs]) =>
        Array.isArray(msgs) ? msgs.map(m => `${field}: ${m}`) : [`${field}: ${msgs}`]
      )
      .join('\n');
  }

  return String(errors ?? 'Unknown error');
}
