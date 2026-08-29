import { Link } from '@inertiajs/react';
import ProblemController from '@/actions/App/Http/Controllers/Admin/ProblemController';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import type { StudentRow } from '@/types/admin';

export default function StudentCardList({
    students,
}: {
    students: StudentRow[];
}) {
    if (students.length === 0) {
        return (
            <p className="text-muted-foreground border-sidebar-border/70 dark:border-sidebar-border rounded-xl border px-4 py-10 text-center text-sm">
                Belum ada mahasiswa. Tambahkan lewat tombol di atas.
            </p>
        );
    }

    return (
        <ul className="space-y-3">
            {students.map((student) => (
                <li
                    key={student.id}
                    className="border-sidebar-border/70 dark:border-sidebar-border space-y-3 rounded-xl border p-4"
                >
                    <div>
                        <p className="font-medium">{student.name}</p>
                        <p className="text-muted-foreground text-xs break-all">
                            {student.email}
                        </p>
                    </div>

                    <p className="text-sm">{student.thesis_title ?? '—'}</p>

                    <div className="flex flex-wrap items-center gap-1.5">
                        <Badge variant="secondary">
                            {student.framework_label ?? 'Framework belum diisi'}
                        </Badge>
                        <Badge variant="default">
                            {student.problems_ready} soal siap
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

                    <div className="flex gap-2">
                        <Button asChild size="sm" className="h-10 flex-1">
                            <Link href={ProblemController.index(student.id)}>
                                Soal
                            </Link>
                        </Button>
                        <Button
                            asChild
                            size="sm"
                            variant="outline"
                            className="h-10 flex-1"
                        >
                            <Link href={StudentController.edit(student.id)}>
                                Ubah
                            </Link>
                        </Button>
                    </div>
                </li>
            ))}
        </ul>
    );
}
