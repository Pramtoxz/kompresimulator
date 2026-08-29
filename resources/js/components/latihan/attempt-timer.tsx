import { useEffect, useState } from 'react';

function format(totalSeconds: number): string {
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;

    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
}

type Props = {
    startedAt: string;
    targetMinutes: number;
};

export default function AttemptTimer({ startedAt, targetMinutes }: Props) {
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

    return (
        <div className="text-right">
            <p
                className={`font-mono text-4xl tabular-nums ${overTarget ? 'text-destructive' : ''}`}
            >
                {format(elapsed)}
            </p>
            <p className="text-muted-foreground text-xs">
                target {targetMinutes} menit
                {overTarget && ' · lewat target'}
            </p>
        </div>
    );
}
