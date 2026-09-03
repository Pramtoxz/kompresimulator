import { Form, Head, Link } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import AttemptController from '@/actions/App/Http/Controllers/Student/AttemptController';
import ProblemPanel from '@/components/latihan/problem-panel';
import TutorChat from '@/components/latihan/tutor-chat';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { index } from '@/routes/latihan';
import type { PracticeProblem } from '@/types/latihan';

type Props = {
    problemId: number;
    problem: PracticeProblem;
    targetMinutes: number;
};

export default function AkhirSoal({
    problemId,
    problem,
    targetMinutes,
}: Props) {
    return (
        <>
            <Head title={problem.title ?? 'Soal ujian'} />

            <main className="safe-x mx-auto w-full max-w-3xl space-y-6 px-4 py-6 pb-32 sm:px-6">
                <Button asChild variant="ghost" size="sm" className="-ml-2">
                    <Link href={index()}>
                        <ChevronLeft />
                        Pilih soal lain
                    </Link>
                </Button>

                <div className="space-y-2">
                    <h1 className="text-xl font-semibold tracking-tight">
                        {problem.title ?? 'Soal ujian'}
                    </h1>
                    <p className="text-muted-foreground text-sm leading-relaxed">
                        Baca soalnya sampai paham dulu. Waktu baru berjalan
                        setelah kamu menekan tombol di bawah, jadi tidak
                        terburu-buru.
                    </p>
                </div>

                <ProblemPanel problem={problem} />
            </main>

            <TutorChat diAtasDock />

            <div className="bg-background/95 border-sidebar-border/60 safe-dock safe-x fixed inset-x-0 bottom-0 z-30 border-t px-4 pt-3 backdrop-blur sm:px-6">
                <Form
                    {...AttemptController.store.form(problemId)}
                    className="mx-auto w-full max-w-3xl"
                >
                    {({ processing }) => (
                        <Button
                            size="lg"
                            disabled={processing}
                            className="h-12 w-full"
                        >
                            {processing && <Spinner />}
                            Mulai kerjakan, hitung {targetMinutes} menit
                        </Button>
                    )}
                </Form>
            </div>
        </>
    );
}
