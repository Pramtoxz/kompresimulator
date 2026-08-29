import { Form } from '@inertiajs/react';
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
    showSoal?: boolean;
};

export default function StepDock({
    attemptId,
    problem,
    label,
    isLastStep,
    showSoal = true,
}: Props) {
    return (
        <div className="bg-background/95 border-sidebar-border/60 safe-dock safe-x fixed inset-x-0 bottom-0 z-30 border-t px-4 pt-3 backdrop-blur sm:px-6">
            <div className="mx-auto flex w-full max-w-5xl gap-2">
                {showSoal && <SoalSheet problem={problem} />}

                {!isLastStep && (
                    <Form
                        {...AttemptController.advance.form(attemptId)}
                        options={{ preserveScroll: true }}
                        className="flex-1"
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
