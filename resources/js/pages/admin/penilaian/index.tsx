import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { show } from '@/routes/admin/reviews';

type Baris = {
    id: number;
    student: string;
    title: string | null;
    level_label: string;
    duration_minutes: number | null;
    target_minutes: number;
    within_target: boolean;
    finished_at: string | null;
    score: number | null;
    reviewed: boolean;
};

export default function ReviewIndex({ attempts }: { attempts: Baris[] }) {
    const belum = attempts.filter((item) => !item.reviewed);

    return (
        <>
            <Head title="Penilaian" />

            <div className="safe-x mx-auto flex w-full max-w-6xl flex-1 flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
                <Heading
                    title="Penilaian"
                    description={`${belum.length} latihan menunggu dinilai dari ${attempts.length} yang sudah selesai.`}
                />

                {attempts.length === 0 && (
                    <p className="text-muted-foreground text-sm">
                        Belum ada latihan yang selesai.
                    </p>
                )}

                <div className="space-y-3">
                    {attempts.map((item) => (
                        <Card key={item.id}>
                            <CardContent className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div className="min-w-0 space-y-1">
                                    <p className="font-medium">
                                        {item.student}
                                    </p>
                                    <p className="text-muted-foreground truncate text-sm">
                                        {item.title ?? 'Soal tanpa judul'} ·{' '}
                                        {item.level_label}
                                    </p>
                                    <div className="flex flex-wrap items-center gap-2">
                                        <Badge
                                            variant={
                                                item.within_target
                                                    ? 'default'
                                                    : 'destructive'
                                            }
                                        >
                                            {item.duration_minutes ?? '—'} dari{' '}
                                            {item.target_minutes} menit
                                        </Badge>
                                        {item.reviewed ? (
                                            <Badge variant="secondary">
                                                Dinilai
                                                {item.score !== null &&
                                                    ` · ${item.score}`}
                                            </Badge>
                                        ) : (
                                            <span className="text-muted-foreground text-xs">
                                                Belum dinilai
                                            </span>
                                        )}
                                        <span className="text-muted-foreground text-xs">
                                            {item.finished_at ?? ''}
                                        </span>
                                    </div>
                                </div>

                                <Button
                                    asChild
                                    variant={
                                        item.reviewed ? 'outline' : 'default'
                                    }
                                    className="shrink-0"
                                >
                                    <Link href={show(item.id)}>
                                        {item.reviewed
                                            ? 'Lihat penilaian'
                                            : 'Nilai sekarang'}
                                    </Link>
                                </Button>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            </div>
        </>
    );
}
