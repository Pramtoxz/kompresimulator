import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { index as penilaian } from '@/routes/admin/reviews';

type Mahasiswa = {
    id: number;
    name: string;
    email: string;
    thesis_title: string | null;
    framework_label: string | null;
    attempts: number;
    finished: number;
    running: { id: number; level_label: string; minutes: number } | null;
    last_seen: string | null;
    chats: number;
    refusals: number;
};

export default function MonitorIndex({ students }: { students: Mahasiswa[] }) {
    const sedangJalan = students.filter((item) => item.running !== null);

    return (
        <AppLayout>
            <Head title="Pantau mahasiswa" />

            <div className="mx-auto w-full max-w-6xl space-y-6 p-4 sm:p-6">
                <Heading
                    title="Pantau mahasiswa"
                    description="Siapa sedang mengerjakan apa, sudah berapa kali latihan, dan kapan terakhir masuk."
                />

                {sedangJalan.length > 0 && (
                    <Card className="border-primary/40">
                        <CardContent className="space-y-2">
                            <p className="font-medium">
                                Sedang mengerjakan sekarang
                            </p>
                            {sedangJalan.map((item) => (
                                <p key={item.id} className="text-sm">
                                    {item.name} · {item.running?.level_label} ·
                                    berjalan {item.running?.minutes} menit
                                </p>
                            ))}
                        </CardContent>
                    </Card>
                )}

                {students.length === 0 && (
                    <p className="text-muted-foreground text-sm">
                        Belum ada mahasiswa.
                    </p>
                )}

                <div className="grid gap-3 md:grid-cols-2">
                    {students.map((item) => (
                        <Card key={item.id}>
                            <CardContent className="space-y-3">
                                <div className="flex items-start justify-between gap-3">
                                    <div className="min-w-0">
                                        <p className="font-medium">
                                            {item.name}
                                        </p>
                                        <p className="text-muted-foreground truncate text-xs">
                                            {item.email}
                                        </p>
                                    </div>
                                    {item.running ? (
                                        <Badge>Sedang mengerjakan</Badge>
                                    ) : (
                                        <Badge variant="secondary">Diam</Badge>
                                    )}
                                </div>

                                <p className="text-muted-foreground text-sm">
                                    {item.thesis_title ?? '—'} ·{' '}
                                    {item.framework_label ?? '—'}
                                </p>

                                <dl className="grid grid-cols-2 gap-x-4 gap-y-1 text-sm sm:grid-cols-4">
                                    <div>
                                        <dt className="text-muted-foreground text-xs">
                                            Latihan
                                        </dt>
                                        <dd className="font-mono">
                                            {item.finished}/{item.attempts}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt className="text-muted-foreground text-xs">
                                            Tanya Bg Dito
                                        </dt>
                                        <dd className="font-mono">
                                            {item.chats}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt className="text-muted-foreground text-xs">
                                            Ditolak
                                        </dt>
                                        <dd className="font-mono">
                                            {item.refusals}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt className="text-muted-foreground text-xs">
                                            Terakhir masuk
                                        </dt>
                                        <dd className="text-xs">
                                            {item.last_seen ?? '—'}
                                        </dd>
                                    </div>
                                </dl>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <Button asChild variant="outline">
                    <Link href={penilaian()}>Buka halaman penilaian</Link>
                </Button>
            </div>
        </AppLayout>
    );
}
