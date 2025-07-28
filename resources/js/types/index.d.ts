import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    permissions: string[];
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    items?: {
      title: string
      href: string
    }[]
}

export interface GroupNavItem {
    title: string;
    items: NavItem[];
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    username: string;
    status: boolean;
    role: Role;
    profile: {
        full_name: string;
        phone?: string;
        address?: string;
        avatar?: string;
    };
}

export interface Permission {
  id: number
  name: string
}

export type PermissionItem = {
  name: string;
};

export interface MenuPermission {
  menu: string,
  view: boolean,
  create: boolean,
  edit: boolean,
  delete: boolean
}

export interface Role {
  id: number
  name: string
  icon: string
  users: User[]
  status: boolean
  permissions: Permission[]
}

export type BreadcrumbItemType = BreadcrumbItem;
