import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { index } from '@/routes/latihan';
import type { PracticeAttempt, PracticeProblem } from '@/types/latihan';

type Props = {
    attempt: PracticeAttempt;
    problem: PracticeProblem;
    within_target: boolean;
    feedback: string | null;
};

function minutes(seconds: number | null): string {
    if (seconds === null) {
        return '—';
    }

    return `${(seconds / 60).toFixed(1)} menit`;
}

export default function AttemptResult({
    attempt,
    problem,
    within_target,
    feedback,
}: Props) {
    return (
        <>
            <Head title="Hasil latihan" />

            <div className="safe-x mx-auto flex w-full max-w-4xl flex-1 flex-col gap-5 px-4 py-6 sm:px-6 lg:px-8">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <Heading
                        title="Hasil latihan"
                        description={problem.title ?? ''}
                    />
                    <Button asChild variant="secondary">
                        <Link href={index()}>Kembali ke latihan</Link>
                    </Button>
                </div>

                <div className="grid gap-4 md:grid-cols-3">
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">Durasi</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p className="font-mono text-3xl">
                                {minutes(attempt.duration_seconds)}
                            </p>
                            <p className="text-muted-foreground text-xs">
                                target {attempt.target_minutes} menit ·{' '}
                                {attempt.duration_source === 'manual'
                                    ? 'diisi sendiri'
                                    : 'dari timer'}
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">Hasil</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Badge
                                variant={
                                    within_target ? 'default' : 'destructive'
                                }
                            >
                                {within_target
                                    ? 'Masuk target'
                                    : 'Lewat target'}
                            </Badge>
                        </CardContent>
                    </Card>

                    {attempt.steps.length > 0 && (
                        <Card>
                            <CardHeader>
                                <CardTitle className="text-base">
                                    Langkah selesai
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="font-mono text-3xl">
                                    {
                                        attempt.steps.filter(
                                            (step) => step.status === 'done',
                                        ).length
                                    }
                                    /{attempt.steps.length}
                                </p>
                            </CardContent>
                        </Card>
                    )}
                </div>

                {attempt.steps.length > 0 && (
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">
                                Waktu per langkah
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <ul className="divide-border divide-y text-sm">
                                {attempt.steps.map((step) => (
                                    <li
                                        key={step.step_key}
                                        className="flex justify-between gap-4 py-2"
                                    >
                                        <span>
                                            {step.step_no}. {step.label}
                                        </span>
                                        <span className="text-muted-foreground font-mono">
                                            {step.duration_seconds === null
                                                ? '—'
                                                : `${(step.duration_seconds / 60).toFixed(1)} mnt`}
                                        </span>
                                    </li>
                                ))}
                            </ul>
                        </CardContent>
                    </Card>
                )}

                {feedback && (
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">Catatan</CardTitle>
                        </CardHeader>
                        <CardContent className="text-sm whitespace-pre-line">
                            {feedback}
                        </CardContent>
                    </Card>
                )}
            </div>
        </>
    );
}
