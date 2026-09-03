import { SidebarTrigger } from '@/components/ui/sidebar';

export function AppSidebarHeader() {
    return (
        <header className="safe-x flex h-12 shrink-0 items-center px-4 md:hidden">
            <SidebarTrigger className="-ml-1" />
        </header>
    );
}
