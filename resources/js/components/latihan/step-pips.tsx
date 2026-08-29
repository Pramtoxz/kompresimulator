import type { PracticeStep } from '@/types/latihan';

export default function StepPips({
    steps,
    currentStep,
}: {
    steps: PracticeStep[];
    currentStep: number;
}) {
    return (
        <div className="flex items-center gap-1.5" aria-hidden>
            {steps.map((step) => (
                <span
                    key={step.step_key}
                    className={`h-1.5 rounded-full transition-all ${
                        step.status === 'done'
                            ? 'bg-primary w-5'
                            : step.step_no === currentStep
                              ? 'bg-primary w-8'
                              : 'bg-muted-foreground/25 w-5'
                    }`}
                />
            ))}
        </div>
    );
}
