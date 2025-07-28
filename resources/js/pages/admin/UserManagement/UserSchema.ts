import { z } from 'zod';
import { profileSchema } from './ProfileSchema';

export const getUserWithProfileSchema = (isEdit: boolean) => {
  return z.object({
    username: z.string().min(1, 'Username is required'),
    password: isEdit
      ? z.string().min(6, 'Password must be at least 6 characters').optional().or(z.literal('')) // optional on edit
      : z.string().min(6, 'Password must be at least 6 characters'),
    status: z.boolean().default(true),
    profile: profileSchema,
    role_id: z.number().int(),
  })
}


export type UserFormData = z.infer<ReturnType<typeof getUserWithProfileSchema>>