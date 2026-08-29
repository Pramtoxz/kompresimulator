import { Head, Link } from '@inertiajs/react';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import StudentCardList from '@/components/admin/student-card-list';
import StudentTable from '@/components/admin/student-table';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import type { StudentRow } from '@/types/admin';

export default function StudentIndex({ students }: { students: StudentRow[] }) {
    return (
        <>
            <Head title="Mahasiswa" />

            <div className="safe-x mx-auto flex w-full max-w-6xl flex-1 flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
                <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <Heading
                        title="Mahasiswa"
                        description="Daftar mahasiswa beserta judul skripsi dan framework yang mereka pakai."
                    />
                    <Button asChild className="h-11 shrink-0 sm:h-10">
                        <Link href={StudentController.create()}>
                            Tambah mahasiswa
                        </Link>
                    </Button>
                </div>

                <div className="md:hidden">
                    <StudentCardList students={students} />
                </div>

                <div className="hidden md:block">
                    <StudentTable students={students} />
                </div>
            </div>
        </>
    );
}

StudentIndex.layout = {
    breadcrumbs: [{ title: 'Mahasiswa', href: StudentController.index() }],
};
