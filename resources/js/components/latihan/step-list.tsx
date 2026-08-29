import type { PracticeStep } from '@/types/latihan';

function minutes(seconds: number | null): string {
    if (seconds === null) {
        return '';
    }

    return `${(seconds / 60).toFixed(1)} mnt`;
}

type Props = {
    steps: PracticeStep[];
    currentStep: number;
};

export default function StepList({ steps, currentStep }: Props) {
    return (
        <ol className="space-y-1">
            {steps.map((step) => {
                const active = step.step_no === currentStep;
                const done = step.status === 'done';

                return (
                    <li
                        key={step.step_key}
                        aria-current={active ? 'step' : undefined}
                        className={`flex items-center gap-3 rounded-lg px-3 py-2.5 ${
                            active ? 'bg-primary/5 ring-primary/30 ring-1' : ''
                        }`}
                    >
                        <span
                            aria-hidden
                            className={`flex size-6 shrink-0 items-center justify-center rounded-full border-2 text-[11px] font-semibold ${
                                done
                                    ? 'border-primary bg-primary text-primary-foreground'
                                    : active
                                      ? 'border-primary text-primary'
                                      : 'border-muted-foreground/30 text-muted-foreground'
                            }`}
                        >
                            {step.step_no}
                        </span>

                        <span
                            className={`min-w-0 flex-1 text-sm ${done ? 'text-muted-foreground' : 'font-medium'}`}
                        >
                            {step.label}
                        </span>

                        <span className="text-muted-foreground shrink-0 font-mono text-xs tabular-nums">
                            {minutes(step.duration_seconds)}
                        </span>
                    </li>
                );
            })}
        </ol>
    );
}
