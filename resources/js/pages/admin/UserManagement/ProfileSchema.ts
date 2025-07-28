import { z } from 'zod';

export const profileSchema = z.object({
  full_name: z.string().min(1, 'Full name is required'),
  phone: z
    .string()
    .regex(/^[0-9+()\-\s]*$/, 'Phone must be a valid number')
    .max(20, 'Phone number too long')
    .optional()
    .nullable()
    .transform((val) => (val === '' ? undefined  : val)),
  address: z
    .string()
    .optional()
    .nullable()
    .transform((val) => (val === '' ? undefined  : val)),
  avatar: z
    .any()
    .optional()
    .transform((val) => (val === '' ? undefined   : val)),
})