import { Head, Link } from '@inertiajs/react';
import { ArrowRight, Clock, Layers } from 'lucide-react';
import AttemptController from '@/actions/App/Http/Controllers/Student/AttemptController';
import HistoryCardList from '@/components/latihan/history-card-list';
import HistoryTable from '@/components/latihan/history-table';
import LevelLadder from '@/components/latihan/level-ladder';
import TutorChat from '@/components/latihan/tutor-chat';
import { Button } from '@/components/ui/button';
import type { PracticeSummary } from '@/types/latihan';

type Props = {
    student: {
        name: string;
        thesis_title: string | null;
        framework_label: string | null;
        target_minutes: number;
    };
    practice: PracticeSummary;
};

function Fakta({
    icon: Icon,
    label,
    nilai,
}: {
    icon: typeof Clock;
    label: string;
    nilai: string;
}) {
    return (
        <div className="flex items-center gap-2.5">
            <Icon className="text-muted-foreground size-4 shrink-0" />
            <span className="text-sm">
                <span className="text-muted-foreground">{label} </span>
                <span className="font-medium">{nilai}</span>
            </span>
        </div>
    );
}

export default function PracticeIndex({ student, practice }: Props) {
    const beres = practice.levels.filter((level) => level.done).length;

    return (
        <>
            <Head title="Latihan" />

            <div className="safe-x mx-auto w-full max-w-5xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
                <section className="space-y-4">
                    <div className="space-y-1.5">
                        <p className="text-muted-foreground text-sm">
                            Halo, {student.name}
                        </p>
                        <h1 className="max-w-2xl text-2xl leading-snug font-semibold tracking-tight text-balance sm:text-3xl">
                            {student.thesis_title ??
                                'Judul skripsimu belum diisi admin'}
                        </h1>
                    </div>

                    <div className="flex flex-wrap gap-x-6 gap-y-2">
                        <Fakta
                            icon={Layers}
                            label="Framework"
                            nilai={student.framework_label ?? 'belum diatur'}
                        />
                        <Fakta
                            icon={Clock}
                            label="Target"
                            nilai={`${student.target_minutes} menit`}
                        />
                    </div>
                </section>

                {practice.running && (
                    <section className="bg-primary text-primary-foreground flex flex-col gap-4 rounded-2xl p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                        <div className="space-y-1">
                            <p className="text-primary-foreground/75 text-sm">
                                Kamu berhenti di tengah jalan
                            </p>
                            <p className="text-lg font-semibold">
                                {practice.running.level_label} masih berjalan
                            </p>
                        </div>

                        <Button
                            asChild
                            size="lg"
                            variant="secondary"
                            className="h-12 shrink-0"
                        >
                            <Link
                                href={AttemptController.show(
                                    practice.running.id,
                                )}
                            >
                                Lanjutkan
                                <ArrowRight />
                            </Link>
                        </Button>
                    </section>
                )}

                <section className="space-y-4">
                    <div className="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                        <h2 className="text-lg font-semibold tracking-tight">
                            Tiga tingkat, satu urutan
                        </h2>
                        <p className="text-muted-foreground text-sm">
                            {beres} dari {practice.levels.length} tingkat sudah
                            kamu lewati
                        </p>
                    </div>

                    <LevelLadder
                        levels={practice.levels}
                        terkunci={practice.running !== null}
                    />
                </section>

                <section className="space-y-3">
                    <h2 className="text-lg font-semibold tracking-tight">
                        Riwayat latihan
                    </h2>

                    <div className="md:hidden">
                        <HistoryCardList rows={practice.history} />
                    </div>

                    <div className="hidden md:block">
                        <HistoryTable rows={practice.history} />
                    </div>
                </section>
            </div>

            <TutorChat />
        </>
    );
}
