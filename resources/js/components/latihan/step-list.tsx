import { Form } from '@inertiajs/react';
import AttemptController from '@/actions/App/Http/Controllers/Student/AttemptController';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import type { PracticeStep } from '@/types/latihan';

function minutes(seconds: number | null): string {
    if (seconds === null) {
        return '';
    }

    return `${(seconds / 60).toFixed(1)} mnt`;
}

type Props = {
    attemptId: number;
    steps: PracticeStep[];
    currentStep: number;
};

export default function StepList({ attemptId, steps, currentStep }: Props) {
    const lastStep = steps.length;

    return (
        <ol className="space-y-2">
            {steps.map((step) => {
                const active = step.step_no === currentStep;
                const done = step.status === 'done';

                return (
                    <li
                        key={step.step_key}
                        className={`flex items-center justify-between gap-4 rounded-lg border px-4 py-3 ${
                            active
                                ? 'border-primary bg-primary/5'
                                : 'border-sidebar-border/70 dark:border-sidebar-border'
                        }`}
                    >
                        <div className="flex items-center gap-3">
                            <span
                                className={`flex size-7 shrink-0 items-center justify-center rounded-full text-xs font-medium ${
                                    done
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-muted text-muted-foreground'
                                }`}
                            >
                                {step.step_no}
                            </span>
                            <span
                                className={
                                    done ? 'text-muted-foreground' : 'font-medium'
                                }
                            >
                                {step.label}
                            </span>
                        </div>

                        <div className="flex items-center gap-3">
                            <span className="text-muted-foreground font-mono text-xs">
                                {minutes(step.duration_seconds)}
                            </span>

                            {active && step.step_no < lastStep && (
                                <Form
                                    {...AttemptController.advance.form(
                                        attemptId,
                                    )}
                                    options={{ preserveScroll: true }}
                                >
                                    {({ processing }) => (
                                        <Button size="sm" disabled={processing}>
                                            {processing && <Spinner />}
                                            Lanjut
                                        </Button>
                                    )}
                                </Form>
                            )}
                        </div>
                    </li>
                );
            })}
        </ol>
    );
}
