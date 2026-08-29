import { Head } from '@inertiajs/react';
import AttemptTimer from '@/components/latihan/attempt-timer';
import CurrentStepDock from '@/components/latihan/current-step-dock';
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

            <div className="safe-x mx-auto flex w-full max-w-6xl flex-1 flex-col gap-5 px-4 pt-4 pb-44 sm:px-6 md:pb-8 lg:px-8">
                <div className="bg-background/95 border-sidebar-border/70 dark:border-sidebar-border sticky top-16 z-20 -mx-4 border-b px-4 pb-3 backdrop-blur sm:-mx-6 sm:px-6 lg:static lg:mx-0 lg:rounded-xl lg:border lg:p-4">
                    <AttemptTimer
                        startedAt={attempt.started_at}
                        targetMinutes={attempt.target_minutes}
                        currentStep={attempt.current_step}
                        totalSteps={attempt.steps.length}
                    />
                </div>

                <div className="grid gap-5 lg:grid-cols-[1fr_22rem] lg:items-start">
                    <div className="order-2 space-y-5 lg:order-1">
                        <ProblemPanel problem={problem} />

                        <div id="selesai" className="scroll-mt-32">
                            <FinishForm attemptId={attempt.id} />
                        </div>
                    </div>

                    <div className="order-1 space-y-4 lg:order-2 lg:sticky lg:top-24">
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

                        <CurrentStepDock
                            attemptId={attempt.id}
                            steps={attempt.steps}
                            currentStep={attempt.current_step}
                        />
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
