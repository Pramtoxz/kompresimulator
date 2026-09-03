import { Form, Head } from '@inertiajs/react';
import AttemptController from '@/actions/App/Http/Controllers/Student/AttemptController';
import ExamTimer from '@/components/latihan/exam-timer';
import ProblemPanel from '@/components/latihan/problem-panel';
import TutorChat from '@/components/latihan/tutor-chat';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import type { PracticeAttempt, PracticeProblem } from '@/types/latihan';

type Props = {
    attempt: PracticeAttempt;
    problem: PracticeProblem;
};

export default function AttemptShow({ attempt, problem }: Props) {
    return (
        <>
            <Head title={problem.title ?? 'Ujian'} />

            <ExamTimer
                startedAt={attempt.started_at}
                targetMinutes={attempt.target_minutes}
            />

            <main className="safe-x mx-auto w-full max-w-3xl space-y-6 px-4 pt-6 pb-32 sm:px-6">
                <div className="space-y-2">
                    <h1 className="text-xl font-semibold tracking-tight">
                        {problem.title ?? 'Soal ujian'}
                    </h1>
                    <p className="text-muted-foreground text-sm leading-relaxed">
                        Kerjakan di komputermu sendiri: install framework
                        kosong, buka editor polos, lalu jalankan tujuh langkah
                        dari hafalan. Sistem cuma menghitung waktumu.
                    </p>
                </div>

                <ProblemPanel problem={problem} />
            </main>

            <TutorChat attemptId={attempt.id} diAtasDock />

            <div className="bg-background/95 border-sidebar-border/60 safe-dock safe-x fixed inset-x-0 bottom-0 z-30 border-t px-4 pt-3 backdrop-blur sm:px-6">
                <Form
                    {...AttemptController.finish.form(attempt.id)}
                    className="mx-auto w-full max-w-3xl"
                >
                    {({ processing }) => (
                        <>
                            <input
                                type="hidden"
                                name="duration_source"
                                value="timer"
                            />
                            <Button
                                size="lg"
                                disabled={processing}
                                className="h-12 w-full"
                            >
                                {processing && <Spinner />}
                                Selesai, hentikan waktu
                            </Button>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}
