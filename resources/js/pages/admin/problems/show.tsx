import { Form, Head, Link } from '@inertiajs/react';
import ProblemController from '@/actions/App/Http/Controllers/Admin/ProblemController';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import ProblemGuides from '@/components/admin/problem-guides';
import ProblemRulesCard from '@/components/admin/problem-rules-card';
import ProblemSchemaCard from '@/components/admin/problem-schema-card';
import ProblemTestCasesCard from '@/components/admin/problem-test-cases-card';
import StatusBadge from '@/components/admin/status-badge';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { ProblemReview } from '@/types/admin';

export default function ProblemShow({ problem }: { problem: ProblemReview }) {
    return (
        <>
            <Head title={problem.title ?? 'Tinjau soal'} />

            <div className="safe-x flex h-full flex-1 flex-col gap-6 px-4 py-5 sm:px-6 lg:px-8">
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

                {problem.brief && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Narasi soal</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <p className="text-sm">{problem.brief}</p>
                            <ul className="list-inside list-disc space-y-1 text-sm">
                                {problem.requirements.map((requirement) => (
                                    <li key={requirement}>{requirement}</li>
                                ))}
                            </ul>
                        </CardContent>
                    </Card>
                )}

                <div className="grid gap-4 lg:grid-cols-2">
                    <ProblemSchemaCard
                        table={problem.schema_spec.table}
                        columns={problem.schema_spec.columns}
                    />
                    <ProblemRulesCard
                        rules={problem.rules}
                        rates={problem.rates}
                    />
                </div>

                <ProblemTestCasesCard testCases={problem.test_cases} />

                <ProblemGuides guides={problem.guides} />

                <div className="flex items-center justify-between gap-4">
                    <p className="text-muted-foreground text-xs">
                        Digenerate oleh {problem.provider ?? '—'} ·{' '}
                        {problem.model ?? '—'}
                    </p>
                    <Form
                        {...ProblemController.destroy.form(problem.id)}
                        onBefore={() => confirm('Hapus soal ini?')}
                    >
                        {({ processing }) => (
                            <Button variant="destructive" disabled={processing}>
                                Hapus soal
                            </Button>
                        )}
                    </Form>
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
