import { z } from 'zod';

export const roleSchema = z.object({
    id: z.number().optional(),
    name: z.string().min(1, { message: 'Role name is required' }),
    icon: z.string().min(1, { message: 'Icon is required'}),
    status: z.boolean().default(true),
    permissions: z
        .array(
            z.object({
                menu: z.string(),
                view: z.boolean().default(false),
                create: z.boolean().default(false),
                edit: z.boolean().default(false),
                delete: z.boolean().default(false),
            })
        )
});

// This is the inferred TypeScript type
export type RoleFormData = z.infer<typeof roleSchema>;
export type PermissionAction = 'view' | 'create' | 'edit' | 'delete';
