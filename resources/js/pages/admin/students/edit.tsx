import { Form, Head, Link } from '@inertiajs/react';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import StudentFormFields from '@/components/admin/student-form-fields';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import type { FrameworkOption, StudentFormValues } from '@/types/admin';

type Props = {
    student: StudentFormValues;
    frameworks: FrameworkOption[];
};

export default function StudentEdit({ student, frameworks }: Props) {
    return (
        <>
            <Head title={`Ubah ${student.name}`} />

            <div className="safe-x flex h-full flex-1 flex-col gap-8 px-4 py-5 sm:px-6 lg:px-8">
                <Heading
                    title="Ubah mahasiswa"
                    description="Mengubah judul skripsi atau framework tidak mengubah soal yang sudah digenerate."
                />

                <Form
                    {...StudentController.update.form(student.id)}
                    className="max-w-xl space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <StudentFormFields
                                errors={errors}
                                frameworks={frameworks}
                                student={student}
                                passwordHint="Kosongkan bila kata sandi tidak diubah."
                            />

                            <div className="flex items-center gap-3">
                                <Button disabled={processing}>
                                    {processing && <Spinner />}
                                    Simpan perubahan
                                </Button>
                                <Button asChild variant="ghost">
                                    <Link href={StudentController.index()}>
                                        Batal
                                    </Link>
                                </Button>
                            </div>
                        </>
                    )}
                </Form>

                <Form
                    {...StudentController.destroy.form(student.id)}
                    options={{ preserveScroll: true }}
                    onBefore={() =>
                        confirm(
                            `Hapus ${student.name} beserta seluruh soal dan riwayat latihannya?`,
                        )
                    }
                    className="max-w-xl"
                >
                    {({ processing }) => (
                        <Button
                            variant="destructive"
                            disabled={processing}
                            data-test="delete-student-button"
                        >
                            Hapus mahasiswa
                        </Button>
                    )}
                </Form>
            </div>
        </>
    );
}

StudentEdit.layout = {
    breadcrumbs: [
        { title: 'Mahasiswa', href: StudentController.index() },
        { title: 'Ubah', href: StudentController.index() },
    ],
};
