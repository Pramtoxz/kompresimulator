import { Form, Head } from '@inertiajs/react';
import StudentController from '@/actions/App/Http/Controllers/Admin/StudentController';
import StudentFormFields from '@/components/admin/student-form-fields';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import type { FrameworkOption } from '@/types/admin';

export default function StudentCreate({
    frameworks,
}: {
    frameworks: FrameworkOption[];
}) {
    return (
        <>
            <Head title="Tambah mahasiswa" />

            <div className="safe-x flex h-full flex-1 flex-col gap-6 px-4 py-5 sm:px-6 lg:px-8">
                <Heading
                    title="Tambah mahasiswa"
                    description="Akun dibuat manual di sini, lalu kredensialnya dikirim ke mahasiswa."
                />

                <Form
                    {...StudentController.store.form()}
                    className="max-w-xl space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <StudentFormFields
                                errors={errors}
                                frameworks={frameworks}
                                passwordHint="Tulis kata sandi yang akan kamu kirim ke mahasiswa."
                            />

                            <Button disabled={processing}>
                                {processing && <Spinner />}
                                Simpan
                            </Button>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

StudentCreate.layout = {
    breadcrumbs: [
        { title: 'Mahasiswa', href: StudentController.index() },
        { title: 'Tambah', href: StudentController.create() },
    ],
};
