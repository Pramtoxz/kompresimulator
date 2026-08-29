import { Form, Head, Link } from '@inertiajs/react';
import AttemptController from '@/actions/App/Http/Controllers/Student/AttemptController';
import Heading from '@/components/heading';
import HistoryCardList from '@/components/latihan/history-card-list';
import HistoryTable from '@/components/latihan/history-table';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinner';
import { index } from '@/routes/latihan';
import type { PracticeSummary } from '@/types/latihan';

type Props = {
    student: {
        name: string;
        thesis_title: string | null;
        framework_label: string | null;
        target_minutes: number;
    };
    practice: PracticeSummary;
};

export default function PracticeIndex({ student, practice }: Props) {
    return (
        <>
            <Head title="Latihan" />

            <div className="safe-x mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
                <Heading
                    title={`Halo, ${student.name}`}
                    description={`${student.thesis_title ?? '—'} · ${student.framework_label ?? '—'} · target ${student.target_minutes} menit`}
                />

                <div className="grid gap-4 md:grid-cols-3">
                    <Card className="md:col-span-1">
                        <CardHeader>
                            <CardTitle>Level Akhir</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <p className="text-muted-foreground text-sm">
                                Kondisi ujian sesungguhnya. Kerjakan di PC
                                sendiri dengan framework kosong dan editor
                                polos. Sistem hanya menampilkan soal dan
                                menghitung waktu.
                            </p>

                            {practice.running !== null && (
                                <Button asChild className="h-12 w-full">
                                    <Link
                                        href={AttemptController.show(
                                            practice.running,
                                        )}
                                    >
                                        Lanjutkan latihan
                                    </Link>
                                </Button>
                            )}

                            {practice.running === null &&
                                practice.available !== null && (
                                    <Form
                                        {...AttemptController.store.form(
                                            practice.available,
                                        )}
                                    >
                                        {({ processing }) => (
                                            <Button
                                                disabled={processing}
                                                className="h-12 w-full"
                                            >
                                                {processing && <Spinner />}
                                                Mulai latihan
                                            </Button>
                                        )}
                                    </Form>
                                )}

                            {practice.running === null &&
                                practice.available === null && (
                                    <p className="text-muted-foreground text-sm">
                                        Belum ada soal. Hubungi admin untuk
                                        menggenerate soal level akhir.
                                    </p>
                                )}
                        </CardContent>
                    </Card>

                    <Card className="opacity-60">
                        <CardHeader>
                            <CardTitle>Level Awal</CardTitle>
                        </CardHeader>
                        <CardContent className="text-muted-foreground text-sm">
                            Tutorial terbimbing dengan editor dan panduan tiap
                            langkah. Belum tersedia.
                        </CardContent>
                    </Card>

                    <Card className="opacity-60">
                        <CardHeader>
                            <CardTitle>Level Menengah</CardTitle>
                        </CardHeader>
                        <CardContent className="text-muted-foreground text-sm">
                            Latihan mandiri dengan pengecekan otomatis. Belum
                            tersedia.
                        </CardContent>
                    </Card>
                </div>

                <div className="space-y-3">
                    <h2 className="text-lg font-medium">Riwayat latihan</h2>
                    <div className="md:hidden">
                        <HistoryCardList rows={practice.history} />
                    </div>

                    <div className="hidden md:block">
                        <HistoryTable rows={practice.history} />
                    </div>
                </div>
            </div>
        </>
    );
}

PracticeIndex.layout = {
    breadcrumbs: [{ title: 'Latihan', href: index() }],
};
