import { Link } from '@inertiajs/react';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import ProblemController from '@/actions/App/Http/Controllers/Admin/ProblemController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import type { StudentRow } from '@/types/admin';
import {
    DataTable,
    EmptyRow,
    TableBody,
    TableCell,
    TableHead,
    TableRow,
} from './data-table';

export default function StudentTable({ students }: { students: StudentRow[] }) {
    return (
        <DataTable>
            <TableHead
                columns={[
                    'Nama',
                    'Judul skripsi',
                    'Framework',
                    'Soal',
                    'Latihan',
                    '',
                ]}
            />
            <TableBody>
                {students.length === 0 && (
                    <EmptyRow
                        colSpan={6}
                        message="Belum ada mahasiswa. Tambahkan lewat tombol di atas."
                    />
                )}

                {students.map((student) => (
                    <TableRow key={student.id}>
                        <TableCell>
                            <div className="font-medium">{student.name}</div>
                            <div className="text-muted-foreground text-xs">
                                {student.email}
                            </div>
                        </TableCell>
                        <TableCell className="max-w-xs">
                            {student.thesis_title ?? '—'}
                        </TableCell>
                        <TableCell className="whitespace-nowrap">
                            {student.framework_label ?? '—'}
                        </TableCell>
                        <TableCell className="whitespace-nowrap">
                            <div className="flex gap-1">
                                <Badge variant="default">
                                    {student.problems_ready} siap
                                </Badge>
                                {student.problems_queued > 0 && (
                                    <Badge variant="secondary">
                                        {student.problems_queued} antre
                                    </Badge>
                                )}
                                {student.problems_failed > 0 && (
                                    <Badge variant="destructive">
                                        {student.problems_failed} gagal
                                    </Badge>
                                )}
                            </div>
                        </TableCell>
                        <TableCell>{student.attempts}</TableCell>
                        <TableCell>
                            <div className="flex justify-end gap-2 whitespace-nowrap">
                                <Button asChild size="sm" variant="secondary">
                                    <Link
                                        href={ProblemController.index(
                                            student.id,
                                        )}
                                    >
                                        Soal
                                    </Link>
                                </Button>
                                <Button asChild size="sm" variant="ghost">
                                    <Link
                                        href={StudentController.edit(
                                            student.id,
                                        )}
                                    >
                                        Ubah
                                    </Link>
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                ))}
            </TableBody>
        </DataTable>
    );
}
