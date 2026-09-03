import { Head, Link } from '@inertiajs/react';
import { AlertTriangle, Radio } from 'lucide-react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
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

const BATAS_WAJAR = 90;

function durasi(menit: number): string {
    if (menit < 60) {
        return `${menit} menit`;
    }

    const jam = Math.floor(menit / 60);
    const sisa = menit % 60;

    return sisa === 0 ? `${jam} jam` : `${jam} jam ${sisa} menit`;
}

function Angka({ label, nilai }: { label: string; nilai: string }) {
    return (
        <div className="min-w-0">
            <dt className="text-muted-foreground text-xs">{label}</dt>
            <dd className="truncate font-mono text-sm">{nilai}</dd>
        </div>
    );
}

export default function MonitorIndex({ students }: { students: Mahasiswa[] }) {
    const berjalan = students.filter((item) => item.running !== null);
    const terbengkalai = berjalan.filter(
        (item) => (item.running?.minutes ?? 0) > BATAS_WAJAR,
    );

    return (
        <>
            <Head title="Pantau mahasiswa" />

            <div className="safe-x mx-auto flex w-full max-w-6xl flex-1 flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
                <Heading
                    title="Pantau mahasiswa"
                    description="Siapa sedang mengerjakan apa, sudah berapa kali latihan, dan kapan terakhir masuk."
                />

                {berjalan.length > 0 && (
                    <section className="space-y-3 rounded-xl border p-4 sm:p-5">
                        <div className="flex flex-wrap items-center gap-x-3 gap-y-1">
                            <h2 className="flex items-center gap-2 font-medium">
                                <Radio className="text-primary size-4" />
                                Sedang berjalan
                            </h2>
                            <span className="text-muted-foreground text-sm">
                                {berjalan.length} latihan belum ditutup
                                {terbengkalai.length > 0 &&
                                    `, ${terbengkalai.length} lewat ${BATAS_WAJAR} menit`}
                            </span>
                        </div>

                        <ul className="divide-border divide-y">
                            {berjalan.map((item) => {
                                const menit = item.running?.minutes ?? 0;
                                const lama = menit > BATAS_WAJAR;

                                return (
                                    <li
                                        key={item.id}
                                        className="flex flex-wrap items-center gap-x-3 gap-y-1 py-2"
                                    >
                                        <span className="font-medium">
                                            {item.name}
                                        </span>
                                        <Badge variant="secondary">
                                            {item.running?.level_label}
                                        </Badge>
                                        <span
                                            className={`font-mono text-sm ${lama ? 'text-destructive' : 'text-muted-foreground'}`}
                                        >
                                            {durasi(menit)}
                                        </span>
                                        {lama && (
                                            <span className="text-muted-foreground flex items-center gap-1 text-xs">
                                                <AlertTriangle className="size-3.5" />
                                                kemungkinan ditinggal
                                            </span>
                                        )}
                                    </li>
                                );
                            })}
                        </ul>
                    </section>
                )}

                {students.length === 0 && (
                    <p className="text-muted-foreground text-sm">
                        Belum ada mahasiswa.
                    </p>
                )}

                <div className="grid gap-4 lg:grid-cols-2">
                    {students.map((item) => (
                        <Card key={item.id}>
                            <CardContent className="space-y-4">
                                <div className="flex items-start justify-between gap-3">
                                    <div className="min-w-0">
                                        <p className="truncate font-medium">
                                            {item.name}
                                        </p>
                                        <p className="text-muted-foreground truncate text-xs">
                                            {item.email}
                                        </p>
                                    </div>
                                    {item.running ? (
                                        <Badge className="shrink-0">
                                            Berjalan
                                        </Badge>
                                    ) : (
                                        <Badge
                                            variant="secondary"
                                            className="shrink-0"
                                        >
                                            Diam
                                        </Badge>
                                    )}
                                </div>

                                <div className="space-y-1">
                                    <p className="text-sm leading-snug">
                                        {item.thesis_title ?? 'Judul belum ada'}
                                    </p>
                                    <p className="text-muted-foreground text-xs">
                                        {item.framework_label ??
                                            'Framework belum diatur'}
                                    </p>
                                </div>

                                <dl className="grid grid-cols-2 gap-x-4 gap-y-3 border-t pt-3 sm:grid-cols-4">
                                    <Angka
                                        label="Latihan"
                                        nilai={`${item.finished}/${item.attempts}`}
                                    />
                                    <Angka
                                        label="Bertanya"
                                        nilai={String(item.chats)}
                                    />
                                    <Angka
                                        label="Ditolak"
                                        nilai={String(item.refusals)}
                                    />
                                    <Angka
                                        label="Terakhir masuk"
                                        nilai={item.last_seen ?? 'belum pernah'}
                                    />
                                </dl>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <Button asChild variant="outline" className="self-start">
                    <Link href={penilaian()}>Buka halaman penilaian</Link>
                </Button>
            </div>
        </>
    );
}
