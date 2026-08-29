import { Link, usePage } from '@inertiajs/react';
import AppLogoIcon from '@/components/app-logo-icon';
import { Button } from '@/components/ui/button';
import { logout } from '@/routes';
import { index } from '@/routes/latihan';
import type { Auth } from '@/types/auth';

export default function StudentLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const { auth } = usePage<{ auth: Auth }>().props;

    return (
        <div className="bg-background min-h-screen">
            <header className="border-sidebar-border/60 bg-background safe-x safe-t sticky top-0 z-30 border-b">
                <div className="mx-auto flex h-14 w-full max-w-5xl items-center justify-between gap-3 px-4 sm:px-6">
                    <Link
                        href={index()}
                        className="flex items-center gap-2 font-medium"
                    >
                        <AppLogoIcon className="size-5 fill-current" />
                        <span>Latihan Kompre</span>
                    </Link>

                    <div className="flex items-center gap-3">
                        <span className="text-muted-foreground hidden text-sm sm:inline">
                            {auth.user?.name}
                        </span>
                        <Button asChild variant="ghost" size="sm">
                            <Link href={logout()} method="post" as="button">
                                Keluar
                            </Link>
                        </Button>
                    </div>
                </div>
            </header>

            {children}
        </div>
    );
}
