import { Form } from '@inertiajs/react';
import AttemptController from '@/actions/App/Http/Controllers/Student/AttemptController';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import type { PracticeStep } from '@/types/latihan';

type Props = {
    attemptId: number;
    steps: PracticeStep[];
    currentStep: number;
};

export default function CurrentStepDock({
    attemptId,
    steps,
    currentStep,
}: Props) {
    const step = steps.find((item) => item.step_no === currentStep);
    const isLastStep = currentStep >= steps.length;

    return (
        <div className="bg-background/95 border-sidebar-border/70 dark:border-sidebar-border safe-dock safe-x fixed inset-x-0 bottom-0 z-30 border-t px-4 pt-3 backdrop-blur md:static md:rounded-xl md:border md:px-4 md:pt-4 md:pb-4">
            <p className="text-muted-foreground mb-2 text-xs">
                Sedang dikerjakan
            </p>
            <p className="mb-3 text-sm font-medium">
                {currentStep}. {step?.label ?? 'Selesai'}
            </p>

            <div className="flex flex-col gap-2 sm:flex-row">
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
                                Langkah selesai
                            </Button>
                        )}
                    </Form>
                )}

                <Button
                    asChild
                    size="lg"
                    variant={isLastStep ? 'default' : 'outline'}
                    className="h-12 sm:w-auto"
                >
                    <a href="#selesai">Tandai latihan selesai</a>
                </Button>
            </div>
        </div>
    );
}
