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

                {practice.running && (
                    <Card className="border-primary/40">
                        <CardContent className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p className="font-medium">
                                    Ada latihan yang belum selesai
                                </p>
                                <p className="text-muted-foreground text-sm">
                                    {practice.running.level_label}
                                </p>
                            </div>
                            <Button asChild className="h-11 sm:h-10">
                                <Link
                                    href={AttemptController.show(
                                        practice.running.id,
                                    )}
                                >
                                    Lanjutkan
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                )}

                <div className="grid gap-4 md:grid-cols-3">
                    {practice.levels.map((level) => (
                        <Card key={level.value} className="flex flex-col">
                            <CardHeader className="pb-3">
                                <CardTitle>{level.label}</CardTitle>
                            </CardHeader>
                            <CardContent className="flex flex-1 flex-col justify-between gap-4">
                                <p className="text-muted-foreground text-sm">
                                    {level.description}
                                </p>

                                {level.problem_id === null ? (
                                    <p className="text-muted-foreground text-sm">
                                        Belum ada soal. Minta admin
                                        menggeneratenya.
                                    </p>
                                ) : (
                                    <Form
                                        {...AttemptController.store.form(
                                            level.problem_id,
                                        )}
                                    >
                                        {({ processing }) => (
                                            <Button
                                                disabled={
                                                    processing ||
                                                    practice.running !== null
                                                }
                                                className="h-12 w-full"
                                            >
                                                {processing && <Spinner />}
                                                Mulai latihan
                                            </Button>
                                        )}
                                    </Form>
                                )}
                            </CardContent>
                        </Card>
                    ))}
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
