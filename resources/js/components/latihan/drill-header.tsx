import { Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { Button } from '@/components/ui/button';
import { faseFor } from '@/lib/fase';
import { index } from '@/routes/latihan';
import type { PracticeStep } from '@/types/latihan';
import StepPips from './step-pips';

function format(seconds: number): string {
    const minutes = Math.floor(seconds / 60);

    return `${String(minutes).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`;
}

type Props = {
    steps: PracticeStep[];
    currentStep: number;
    startedAt: string;
    targetMinutes: number;
    showTimer: boolean;
};

export default function DrillHeader({
    steps,
    currentStep,
    startedAt,
    targetMinutes,
    showTimer,
}: Props) {
    const [elapsed, setElapsed] = useState(0);

    useEffect(() => {
        if (!showTimer) {
            return;
        }

        const start = new Date(startedAt).getTime();
        const tick = () =>
            setElapsed(Math.max(0, Math.floor((Date.now() - start) / 1000)));

        tick();
        const timer = window.setInterval(tick, 1000);

        return () => window.clearInterval(timer);
    }, [startedAt, showTimer]);

    const overTarget = elapsed > targetMinutes * 60;
    const active = steps.find((step) => step.step_no === currentStep);
    const fase = faseFor(active?.step_key ?? '');

    return (
        <header className="border-sidebar-border/60 bg-background/95 safe-x safe-t sticky top-0 z-30 border-b backdrop-blur">
            <div className="mx-auto w-full max-w-5xl px-4 py-3 sm:px-6">
                <div className="flex items-center justify-between gap-4">
                    <div className="min-w-0 space-y-2" data-tour="langkah">
                        <p className="flex items-center gap-2 text-xs">
                            <span
                                className={`size-2 shrink-0 rounded-full ${fase.dot}`}
                            />
                            <span className={fase.text}>{fase.label}</span>
                            <span className="text-muted-foreground truncate">
                                langkah {currentStep} dari {steps.length}
                            </span>
                        </p>
                        <StepPips steps={steps} currentStep={currentStep} />
                    </div>

                    <div className="flex shrink-0 items-center gap-3">
                        {showTimer && (
                            <p
                                className={`font-mono text-2xl leading-none tabular-nums ${overTarget ? 'text-destructive' : ''}`}
                            >
                                {format(elapsed)}
                            </p>
                        )}

                        <Button asChild variant="ghost" size="sm">
                            <Link href={index()}>Keluar</Link>
                        </Button>
                    </div>
                </div>
            </div>
        </header>
    );
}
