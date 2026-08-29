import { Form, Head, Link, router } from '@inertiajs/react';
import { useEffect } from 'react';
import ProblemController from '@/actions/App/Http/Controllers/Admin/ProblemController';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import {
    DataTable,
    EmptyRow,
    TableBody,
    TableCell,
    TableHead,
    TableRow,
} from '@/components/admin/data-table';
import StatusBadge from '@/components/admin/status-badge';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import type { LevelOption, ProblemRow } from '@/types/admin';

type Props = {
    student: {
        id: number;
        name: string;
        thesis_title: string | null;
        framework_label: string | null;
    };
    problems: ProblemRow[];
    levels: LevelOption[];
};

export default function ProblemIndex({ student, problems, levels }: Props) {
    const queued = problems.filter(
        (problem) => problem.status === 'queued',
    ).length;

    useEffect(() => {
        if (queued === 0) {
            return;
        }

        const timer = window.setInterval(
            () => router.reload({ only: ['problems'] }),
            4000,
        );

        return () => window.clearInterval(timer);
    }, [queued]);

    return (
        <>
            <Head title={`Soal ${student.name}`} />

            <div className="safe-x mx-auto flex w-full max-w-6xl flex-1 flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
                <Heading
                    title={`Soal untuk ${student.name}`}
                    description={`${student.thesis_title ?? '—'} · ${student.framework_label ?? '—'}`}
                />

                <Form
                    {...ProblemController.store.form(student.id)}
                    options={{ preserveScroll: true }}
                    className="flex flex-wrap items-end gap-3"
                >
                    {({ processing }) => (
                        <>
                            <div className="grid gap-2">
                                <label
                                    htmlFor="level"
                                    className="text-sm font-medium"
                                >
                                    Generate soal baru
                                </label>
                                <select
                                    id="level"
                                    name="level"
                                    defaultValue={levels[0]?.value}
                                    className="border-input bg-background h-9 rounded-md border px-3 text-sm shadow-xs"
                                >
                                    {levels.map((level) => (
                                        <option
                                            key={level.value}
                                            value={level.value}
                                        >
                                            {level.label}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            <Button disabled={processing}>
                                {processing && <Spinner />}
                                Generate
                            </Button>
                        </>
                    )}
                </Form>

                {queued > 0 && (
                    <div className="space-y-1 rounded-xl border border-amber-500/40 bg-amber-500/5 p-4">
                        <p className="text-sm font-medium">
                            {queued} soal sedang digenerate
                        </p>
                        <p className="text-muted-foreground text-sm">
                            Prosesnya berjalan di latar belakang dan memakan 20
                            sampai 30 detik. Halaman ini menyegarkan diri
                            sendiri. Kalau statusnya tidak berubah lebih dari
                            satu menit, berarti pekerja antrean belum jalan —
                            jalankan{' '}
                            <span className="font-mono text-xs">
                                php artisan queue:work
                            </span>{' '}
                            di terminal terpisah.
                        </p>
                    </div>
                )}

                <DataTable>
                    <TableHead
                        columns={[
                            'Level',
                            'Judul',
                            'Status',
                            'Test case',
                            'Panduan',
                            'Dibuat',
                            '',
                        ]}
                    />
                    <TableBody>
                        {problems.length === 0 && (
                            <EmptyRow
                                colSpan={7}
                                message="Belum ada soal. Pilih level lalu tekan Generate."
                            />
                        )}

                        {problems.map((problem) => (
                            <TableRow key={problem.id}>
                                <TableCell className="whitespace-nowrap">
                                    {problem.level_label}
                                </TableCell>
                                <TableCell className="max-w-sm">
                                    {problem.title ?? '—'}
                                    {problem.failure_reason && (
                                        <p className="text-destructive mt-1 text-xs">
                                            {problem.failure_reason}
                                        </p>
                                    )}
                                </TableCell>
                                <TableCell>
                                    <StatusBadge status={problem.status} />
                                </TableCell>
                                <TableCell>{problem.test_cases}</TableCell>
                                <TableCell>{problem.guides}</TableCell>
                                <TableCell className="whitespace-nowrap">
                                    {problem.created_at}
                                </TableCell>
                                <TableCell>
                                    <div className="flex justify-end whitespace-nowrap">
                                        <Button
                                            asChild
                                            size="sm"
                                            variant="secondary"
                                        >
                                            <Link
                                                href={ProblemController.show(
                                                    problem.id,
                                                )}
                                            >
                                                Tinjau
                                            </Link>
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </DataTable>
            </div>
        </>
    );
}

ProblemIndex.layout = {
    breadcrumbs: [
        { title: 'Mahasiswa', href: StudentController.index() },
        { title: 'Soal', href: StudentController.index() },
    ],
};
