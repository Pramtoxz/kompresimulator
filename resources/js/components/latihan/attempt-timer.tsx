import { useEffect, useState } from 'react';

function format(totalSeconds: number): string {
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;

    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
}

type Props = {
    startedAt: string;
    targetMinutes: number;
    currentStep: number;
    totalSteps: number;
};

export default function AttemptTimer({
    startedAt,
    targetMinutes,
    currentStep,
    totalSteps,
}: Props) {
    const [elapsed, setElapsed] = useState(0);

    useEffect(() => {
        const start = new Date(startedAt).getTime();

        const tick = () =>
            setElapsed(Math.max(0, Math.floor((Date.now() - start) / 1000)));

        tick();
        const timer = window.setInterval(tick, 1000);

        return () => window.clearInterval(timer);
    }, [startedAt]);

    const target = targetMinutes * 60;
    const overTarget = elapsed > target;
    const progress = Math.min(100, (elapsed / target) * 100);

    return (
        <div className="space-y-2">
            <div className="flex items-baseline justify-between gap-4">
                <p
                    className={`font-mono text-3xl leading-none tabular-nums sm:text-4xl ${overTarget ? 'text-destructive' : ''}`}
                >
                    {format(elapsed)}
                </p>
                <p className="text-muted-foreground text-sm">
                    Langkah {currentStep} dari {totalSteps}
                </p>
            </div>

            <div
                className="bg-muted h-1 w-full overflow-hidden rounded-full"
                role="progressbar"
                aria-valuemin={0}
                aria-valuemax={target}
                aria-valuenow={Math.min(elapsed, target)}
                aria-label={`Waktu berjalan, target ${targetMinutes} menit`}
            >
                <div
                    className={`h-full transition-[width] duration-1000 ease-linear ${overTarget ? 'bg-destructive' : 'bg-primary'}`}
                    style={{ width: `${overTarget ? 100 : progress}%` }}
                />
            </div>

            <p className="text-muted-foreground text-xs">
                {overTarget
                    ? `Lewat target ${targetMinutes} menit. Terus jalan sampai selesai.`
                    : `Target ${targetMinutes} menit.`}
            </p>
        </div>
    );
}
