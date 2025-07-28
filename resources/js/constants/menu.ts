import { type GroupNavItem } from '@/types';
import { Cog, LayoutGrid, ShoppingBag } from 'lucide-vue-next';
// Don't forgot to update database seeder after update here..
export const menuList: GroupNavItem[] = [
    {
        title: 'Platform',
        items: [
            {
                title: 'Dashboard',
                href: '/',
                icon: LayoutGrid,
            },
            {
                title: 'Product',
                href: '/ss',
                icon: ShoppingBag,
            },
        ]
    },
    {
        title: 'Admin',
        items: [
            {
                title: 'Account',
                href: '/admin/roles',
                icon: Cog,
                items: [
                    {
                        title: 'Roles Management',
                        href: '/admin/roles',
                    },
                    {
                        title: 'Users Management',
                        href: '/admin/users',
                    }
                ]
            },
        ]
    }
]

export function permissionMenu() {
  return menuList.flatMap(group =>
    group.items.flatMap(menu =>
      (menu.items ?? [menu]).map(item => ({
        menu: item.title,
        view: false,
        create: false,
        edit: false,
        delete: false,
      }))
    )
  );
}
