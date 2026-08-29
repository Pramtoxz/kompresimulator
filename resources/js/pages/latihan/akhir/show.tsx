import { Head } from '@inertiajs/react';
import AttemptTimer from '@/components/latihan/attempt-timer';
import FinishForm from '@/components/latihan/finish-form';
import ProblemPanel from '@/components/latihan/problem-panel';
import StepList from '@/components/latihan/step-list';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { index } from '@/routes/latihan';
import type { PracticeAttempt, PracticeProblem } from '@/types/latihan';

type Props = {
    attempt: PracticeAttempt;
    problem: PracticeProblem;
};

export default function AttemptShow({ attempt, problem }: Props) {
    return (
        <>
            <Head title="Latihan berjalan" />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h1 className="text-xl font-medium">
                            Kerjakan di komputermu sendiri
                        </h1>
                        <p className="text-muted-foreground max-w-2xl text-sm">
                            Install framework kosong, buka editor polos, dan
                            jalankan tujuh langkah dari hafalan. Tekan Lanjut
                            setiap satu langkah beres supaya sistem tahu kamu
                            tersendat di mana.
                        </p>
                    </div>

                    <AttemptTimer
                        startedAt={attempt.started_at}
                        targetMinutes={attempt.target_minutes}
                    />
                </div>

                <div className="grid gap-6 lg:grid-cols-[2fr_1fr]">
                    <ProblemPanel problem={problem} />

                    <div className="space-y-4">
                        <Card>
                            <CardHeader>
                                <CardTitle className="text-base">
                                    Tujuh langkah
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <StepList
                                    attemptId={attempt.id}
                                    steps={attempt.steps}
                                    currentStep={attempt.current_step}
                                />
                            </CardContent>
                        </Card>

                        <FinishForm attemptId={attempt.id} />
                    </div>
                </div>
            </div>
        </>
    );
}

AttemptShow.layout = {
    breadcrumbs: [
        { title: 'Latihan', href: index() },
        { title: 'Berjalan', href: index() },
    ],
};
