import { Form, router } from '@inertiajs/react';
import { ChevronLeft } from 'lucide-react';
import AttemptController from '@/actions/App/Http/Controllers/Student/AttemptController';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import type { PracticeProblem } from '@/types/latihan';
import SoalSheet from './soal-sheet';

type Props = {
    attemptId: number;
    problem: PracticeProblem;
    label: string;
    isLastStep: boolean;
    canGoBack: boolean;
    showSoal?: boolean;
};

export default function StepDock({
    attemptId,
    problem,
    label,
    isLastStep,
    canGoBack,
    showSoal = true,
}: Props) {
    const goBack = () =>
        router.post(
            AttemptController.back.url(attemptId),
            {},
            { preserveScroll: true },
        );

    return (
        <div className="bg-background/95 border-sidebar-border/60 safe-dock safe-x fixed inset-x-0 bottom-0 z-30 border-t px-4 pt-3 backdrop-blur sm:px-6">
            <div className="mx-auto flex w-full max-w-5xl gap-2">
                {canGoBack && (
                    <Button
                        type="button"
                        variant="outline"
                        size="lg"
                        onClick={goBack}
                        className="h-12 shrink-0 px-3 sm:px-4"
                        data-tour="mundur"
                    >
                        <ChevronLeft />
                        <span className="hidden sm:inline">Langkah tadi</span>
                        <span className="sr-only sm:hidden">
                            Kembali ke langkah sebelumnya
                        </span>
                    </Button>
                )}

                {showSoal && <SoalSheet problem={problem} />}

                {!isLastStep && (
                    <Form
                        {...AttemptController.advance.form(attemptId)}
                        options={{ preserveScroll: true }}
                        className="flex-1"
                        data-tour="lanjut"
                    >
                        {({ processing }) => (
                            <Button
                                size="lg"
                                disabled={processing}
                                className="h-12 w-full"
                            >
                                {processing && <Spinner />}
                                {label}
                            </Button>
                        )}
                    </Form>
                )}
            </div>
        </div>
    );
}
