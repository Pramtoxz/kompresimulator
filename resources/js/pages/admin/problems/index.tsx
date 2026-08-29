import { Form, Head, Link } from '@inertiajs/react';
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
    return (
        <>
            <Head title={`Soal ${student.name}`} />

            <div className="safe-x flex h-full flex-1 flex-col gap-6 px-4 py-5 sm:px-6 lg:px-8">
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
