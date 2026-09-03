import { Link, usePage } from '@inertiajs/react';
import { LogOut } from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useInitials } from '@/hooks/use-initials';
import { logout } from '@/routes';
import { index } from '@/routes/latihan';
import type { Auth } from '@/types/auth';

export default function StudentLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const { auth } = usePage<{ auth: Auth }>().props;
    const initials = useInitials();
    const nama = auth.user?.name ?? '';

    return (
        <div className="bg-muted/25 flex min-h-svh flex-col">
            <header className="border-border/70 bg-background/85 safe-x safe-t sticky top-0 z-30 border-b backdrop-blur-md">
                <div className="mx-auto flex h-16 w-full max-w-5xl items-center justify-between gap-3 px-4 sm:px-6">
                    <Link
                        href={index()}
                        className="group flex items-center gap-2.5"
                    >
                        <AppLogoIcon className="size-8 shrink-0 rounded-lg object-contain" />
                        <span className="leading-none">
                            <span className="block font-semibold tracking-tight">
                                Latihan Kompre
                            </span>
                            <span className="text-muted-foreground mt-0.5 block font-mono text-[11px]">
                                tujuh langkah, tiga puluh menit
                            </span>
                        </span>
                    </Link>

                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <button
                                type="button"
                                className="ring-offset-background focus-visible:ring-ring flex items-center gap-2 rounded-full py-1 pr-1 pl-3 transition-colors focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none"
                            >
                                <span className="hidden text-sm font-medium sm:inline">
                                    {nama}
                                </span>
                                <span className="bg-primary text-primary-foreground flex size-9 items-center justify-center rounded-full text-xs font-semibold">
                                    {initials(nama)}
                                </span>
                            </button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent align="end" className="w-56">
                            <DropdownMenuLabel className="font-normal">
                                <span className="block text-sm font-medium">
                                    {nama}
                                </span>
                                <span className="text-muted-foreground block truncate text-xs">
                                    {auth.user?.email}
                                </span>
                            </DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem asChild>
                                <Link href={logout()} method="post" as="button">
                                    <LogOut />
                                    Keluar
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </header>

            <div className="flex flex-1 flex-col">{children}</div>
        </div>
    );
}
