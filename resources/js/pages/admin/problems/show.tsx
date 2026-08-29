import { Form, Head, Link } from '@inertiajs/react';
import ProblemController from '@/actions/App/Http/Controllers/Admin/ProblemController';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import ProblemGuides from '@/components/admin/problem-guides';
import ExamSheet from '@/components/soal/exam-sheet';
import ProblemTestCasesCard from '@/components/admin/problem-test-cases-card';
import StatusBadge from '@/components/admin/status-badge';
import ConfirmDialog from '@/components/confirm-dialog';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { ProblemReview } from '@/types/admin';

export default function ProblemShow({ problem }: { problem: ProblemReview }) {
    return (
        <>
            <Head title={problem.title ?? 'Tinjau soal'} />

            <div className="safe-x mx-auto flex w-full max-w-4xl flex-1 flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <Heading
                        title={problem.title ?? 'Soal belum siap'}
                        description={`${problem.student_name} · ${problem.level_label} · ${problem.framework_label}`}
                    />
                    <div className="flex items-center gap-3">
                        <StatusBadge status={problem.status} />
                        <Button asChild variant="ghost">
                            <Link
                                href={ProblemController.index(
                                    problem.student_id,
                                )}
                            >
                                Kembali
                            </Link>
                        </Button>
                    </div>
                </div>

                {problem.failure_reason && (
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-destructive">
                                Generate gagal
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="text-sm">
                            {problem.failure_reason}
                        </CardContent>
                    </Card>
                )}

                <ExamSheet
                    title={problem.title}
                    brief={problem.brief}
                    requirements={problem.requirements}
                    formFields={problem.form_fields}
                    lookup={problem.lookup}
                    rules={problem.rules}
                    table={problem.schema_spec.table ?? null}
                    showExpression
                />

                <Card>
                    <CardHeader className="pb-3">
                        <CardTitle className="text-base">
                            Kolom tabel
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ul className="divide-border divide-y text-sm">
                            {(problem.schema_spec.columns ?? []).map(
                                (column) => (
                                    <li
                                        key={column.name}
                                        className="flex justify-between gap-4 py-2"
                                    >
                                        <span className="font-mono">
                                            {column.name}
                                        </span>
                                        <span className="text-muted-foreground">
                                            {column.type}
                                            {column.nullable
                                                ? ' · nullable'
                                                : ''}
                                        </span>
                                    </li>
                                ),
                            )}
                        </ul>
                    </CardContent>
                </Card>

                <ProblemTestCasesCard testCases={problem.test_cases} />

                <ProblemGuides guides={problem.guides} />

                <div className="flex items-center justify-between gap-4">
                    <p className="text-muted-foreground text-xs">
                        Digenerate oleh {problem.provider ?? '—'} ·{' '}
                        {problem.model ?? '—'}
                    </p>
                    <ConfirmDialog
                        trigger="Hapus soal"
                        title="Hapus soal ini?"
                        description="Panduan, test case, dan riwayat pengecekannya ikut terhapus. Tindakan ini tidak bisa dibatalkan."
                        confirmLabel="Ya, hapus"
                        action={ProblemController.destroy.form(problem.id)}
                        triggerClassName="h-11 sm:h-10"
                    />
                </div>
            </div>
        </>
    );
}

ProblemShow.layout = {
    breadcrumbs: [
        { title: 'Mahasiswa', href: StudentController.index() },
        { title: 'Tinjau soal', href: StudentController.index() },
    ],
};
