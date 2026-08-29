import { Head, Link } from '@inertiajs/react';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import StudentTable from '@/components/admin/student-table';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import type { StudentRow } from '@/types/admin';

export default function StudentIndex({ students }: { students: StudentRow[] }) {
    return (
        <>
            <Head title="Mahasiswa" />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading
                        title="Mahasiswa"
                        description="Daftar mahasiswa beserta judul skripsi dan framework yang mereka pakai."
                    />
                    <Button asChild>
                        <Link href={StudentController.create()}>
                            Tambah mahasiswa
                        </Link>
                    </Button>
                </div>

                <StudentTable students={students} />
            </div>
        </>
    );
}

StudentIndex.layout = {
    breadcrumbs: [{ title: 'Mahasiswa', href: StudentController.index() }],
};
