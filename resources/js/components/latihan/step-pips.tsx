import { faseFor } from '@/lib/fase';
import type { PracticeStep } from '@/types/latihan';

export default function StepPips({
    steps,
    currentStep,
}: {
    steps: PracticeStep[];
    currentStep: number;
}) {
    return (
        <ol className="flex items-center gap-1.5">
            {steps.map((step) => {
                const fase = faseFor(step.step_key);
                const current = step.step_no === currentStep;
                const passed = step.status === 'done';

                return (
                    <li
                        key={step.step_key}
                        aria-current={current ? 'step' : undefined}
                        className={`h-1.5 rounded-full transition-all ${
                            current
                                ? `w-8 ${fase.dot}`
                                : passed
                                  ? `w-5 ${fase.dot} opacity-45`
                                  : 'bg-muted-foreground/20 w-5'
                        }`}
                    >
                        <span className="sr-only">
                            {step.step_no}. {step.label}
                        </span>
                    </li>
                );
            })}
        </ol>
    );
}
