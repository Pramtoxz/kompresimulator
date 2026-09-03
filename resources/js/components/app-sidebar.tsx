import { Link, usePage } from '@inertiajs/react';
import {
    ClipboardCheck,
    GraduationCap,
    LayoutGrid,
    MonitorDot,
    ShieldCheck,
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import { dashboard } from '@/routes';
import { index as authLog } from '@/routes/admin/auth-log';
import { index as monitor } from '@/routes/admin/monitor';
import { index as reviews } from '@/routes/admin/reviews';
import { index as practiceIndex } from '@/routes/latihan';
import type { Auth } from '@/types/auth';
import type { NavItem } from '@/types';

const adminNavItems: NavItem[] = [
    {
        title: 'Mahasiswa',
        href: StudentController.index(),
        icon: GraduationCap,
    },
    {
        title: 'Riwayat masuk',
        href: authLog(),
        icon: ShieldCheck,
    },
    {
        title: 'Pantau mahasiswa',
        href: monitor(),
        icon: MonitorDot,
    },
    {
        title: 'Penilaian',
        href: reviews(),
        icon: ClipboardCheck,
    },
];

const studentNavItems: NavItem[] = [
    {
        title: 'Latihan',
        href: practiceIndex(),
        icon: LayoutGrid,
    },
];

export function AppSidebar() {
    const { auth } = usePage<{ auth: Auth }>().props;
    const navItems =
        auth.user?.role === 'admin' ? adminNavItems : studentNavItems;

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={navItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
