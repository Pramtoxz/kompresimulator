import { Form } from '@inertiajs/react';
import { Check, Lock } from 'lucide-react';
import AttemptController from '@/actions/App/Http/Controllers/Student/AttemptController';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import type { PracticeLevel } from '@/types/latihan';
import SoalPicker from './soal-picker';

function Nomor({ urutan, selesai }: { urutan: number; selesai: boolean }) {
    return (
        <span
            className={`relative z-10 flex size-9 shrink-0 items-center justify-center rounded-full text-sm font-semibold ${
                selesai
                    ? 'bg-primary text-primary-foreground'
                    : 'bg-background text-muted-foreground border-2'
            }`}
        >
            {selesai ? <Check className="size-4" /> : urutan}
        </span>
    );
}

function Status({ level }: { level: PracticeLevel }) {
    if (level.repeatable) {
        const total = level.problems.length;
        const beres = level.problems.filter((soal) => soal.done).length;

        return (
            <span className="text-muted-foreground text-xs">
                {total === 0
                    ? 'Soal belum tersedia'
                    : `${beres} dari ${total} soal selesai`}
            </span>
        );
    }

    return (
        <span
            className={`text-xs ${level.done ? 'text-primary' : 'text-muted-foreground'}`}
        >
            {level.done ? 'Sudah kamu selesaikan' : 'Belum dikerjakan'}
        </span>
    );
}

function Aksi({
    level,
    terkunci,
}: {
    level: PracticeLevel;
    terkunci: boolean;
}) {
    if (level.problems.length > 0) {
        return <SoalPicker soal={level.problems} terkunci={terkunci} />;
    }

    if (level.problem_id === null) {
        return (
            <p className="text-muted-foreground flex items-center gap-1.5 text-sm">
                <Lock className="size-3.5" />
                Menunggu admin
            </p>
        );
    }

    return (
        <Form {...AttemptController.store.form(level.problem_id)}>
            {({ processing }) => (
                <Button
                    disabled={processing || terkunci}
                    variant={level.done ? 'outline' : 'default'}
                    className="h-11 w-full sm:w-auto"
                >
                    {processing && <Spinner />}
                    {level.done ? 'Ulangi' : 'Mulai'}
                </Button>
            )}
        </Form>
    );
}

export default function LevelLadder({
    levels,
    terkunci,
}: {
    levels: PracticeLevel[];
    terkunci: boolean;
}) {
    return (
        <ol className="relative">
            {levels.map((level, urutan) => (
                <li key={level.value} className="relative flex gap-4 pb-5">
                    {urutan < levels.length - 1 && (
                        <span
                            aria-hidden
                            className="bg-border absolute top-9 bottom-0 left-[1.0625rem] w-0.5"
                        />
                    )}

                    <Nomor urutan={urutan + 1} selesai={level.done} />

                    <div className="bg-background flex flex-1 flex-col gap-3 rounded-xl border p-4 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
                        <div className="min-w-0 space-y-1">
                            <div className="flex flex-wrap items-baseline gap-x-2">
                                <h3 className="font-semibold">{level.label}</h3>
                                <Status level={level} />
                            </div>
                            <p className="text-muted-foreground text-sm leading-relaxed">
                                {level.description}
                            </p>
                        </div>

                        <div className="shrink-0">
                            <Aksi level={level} terkunci={terkunci} />
                        </div>
                    </div>
                </li>
            ))}
        </ol>
    );
}
