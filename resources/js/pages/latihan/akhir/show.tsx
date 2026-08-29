import { Head } from '@inertiajs/react';
import DrillHeader from '@/components/latihan/drill-header';
import FinishForm from '@/components/latihan/finish-form';
import ProblemPanel from '@/components/latihan/problem-panel';
import StepDock from '@/components/latihan/step-dock';
import StepList from '@/components/latihan/step-list';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { PracticeAttempt, PracticeProblem } from '@/types/latihan';

type Props = {
    attempt: PracticeAttempt;
    problem: PracticeProblem;
};

export default function AttemptShow({ attempt, problem }: Props) {
    const isLastStep = attempt.current_step >= attempt.steps.length;

    return (
        <>
            <Head title={problem.title ?? 'Latihan'} />

            <DrillHeader
                steps={attempt.steps}
                currentStep={attempt.current_step}
                startedAt={attempt.started_at}
                targetMinutes={attempt.target_minutes}
                showTimer
            />

            <main className="safe-x mx-auto w-full max-w-3xl space-y-6 px-4 pt-6 pb-32 sm:px-6">
                <div className="space-y-2">
                    <h1 className="text-xl font-semibold tracking-tight">
                        Kerjakan di komputermu sendiri
                    </h1>
                    <p className="text-muted-foreground text-sm leading-relaxed">
                        Install framework kosong, buka editor polos, lalu
                        jalankan tujuh langkah dari hafalan. Tekan Langkah
                        selesai setiap satu langkah beres supaya sistem tahu
                        kamu tersendat di mana.
                    </p>
                </div>

                <ProblemPanel problem={problem} />

                <Card>
                    <CardHeader className="pb-2">
                        <CardTitle className="text-base">
                            Tujuh langkah
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="px-2">
                        <StepList
                            steps={attempt.steps}
                            currentStep={attempt.current_step}
                        />
                    </CardContent>
                </Card>

                {isLastStep && <FinishForm attemptId={attempt.id} />}
            </main>

            <StepDock
                attemptId={attempt.id}
                problem={problem}
                label="Langkah selesai"
                isLastStep={isLastStep}
                showSoal={false}
            />
        </>
    );
}
