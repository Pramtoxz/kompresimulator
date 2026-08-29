import { Form, Head, Link } from '@inertiajs/react';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import StudentFormFields from '@/components/admin/student-form-fields';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
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

            <div className="safe-x mx-auto flex w-full max-w-2xl flex-1 flex-col gap-8 px-4 py-6 sm:px-6 lg:px-8">
                <Heading
                    title="Ubah mahasiswa"
                    description="Mengubah judul skripsi atau framework tidak mengubah soal yang sudah digenerate."
                />

                <Card>
                    <CardContent>
                        <Form
                            {...StudentController.update.form(student.id)}
                            className="space-y-6"
                        >
                            {({ processing, errors }) => (
                                <>
                                    <StudentFormFields
                                        errors={errors}
                                        frameworks={frameworks}
                                        student={student}
                                        passwordHint="Kosongkan bila kata sandi tidak diubah."
                                    />

                                    <div className="flex flex-col gap-2 sm:flex-row sm:items-center">
                                        <Button
                                            disabled={processing}
                                            className="h-11 sm:h-10"
                                        >
                                            {processing && <Spinner />}
                                            Simpan perubahan
                                        </Button>
                                        <Button
                                            asChild
                                            variant="ghost"
                                            className="h-11 sm:h-10"
                                        >
                                            <Link href={StudentController.index()}>
                                                Batal
                                            </Link>
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>

                <div className="border-destructive/30 space-y-3 rounded-xl border p-4">
                    <div>
                        <p className="text-sm font-medium">Hapus mahasiswa</p>
                        <p className="text-muted-foreground text-sm">
                            Seluruh soal dan riwayat latihannya ikut terhapus dan
                            tidak bisa dikembalikan.
                        </p>
                    </div>

                    <Form
                        {...StudentController.destroy.form(student.id)}
                        options={{ preserveScroll: true }}
                        onBefore={() =>
                            confirm(
                                `Hapus ${student.name} beserta seluruh soal dan riwayat latihannya?`,
                            )
                        }
                    >
                        {({ processing }) => (
                            <Button
                                variant="destructive"
                                disabled={processing}
                                className="h-11 w-full sm:h-10 sm:w-auto"
                                data-test="delete-student-button"
                            >
                                Hapus mahasiswa
                            </Button>
                        )}
                    </Form>
                </div>
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
