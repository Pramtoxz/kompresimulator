import { Form } from '@inertiajs/react';
import WorkspaceController from '@/actions/App/Http/Controllers/Student/WorkspaceController';
import { Button } from '@/components/ui/button';
import type { Briefing, WorkspaceGuide } from '@/types/latihan';
import BriefingDialog from './briefing-dialog';
import StepCards from './step-cards';

type Props = {
    guide: WorkspaceGuide | null;
    briefing: Briefing | null;
    briefingAudio: string[];
    attemptId: number;
    guided: boolean;
};

export default function StepInstruction({
    guide,
    briefing,
    briefingAudio,
    attemptId,
    guided,
}: Props) {
    if (guide === null) {
        return null;
    }

    const canReveal = !guided && !guide.revealed && guide.has_example_code;

    return (
        <section className="space-y-4">
            <div className="space-y-2">
                <h1 className="text-xl font-semibold tracking-tight">
                    Langkah {guide.step_no} — {guide.label}
                </h1>

                {briefing && (
                    <BriefingDialog
                        briefing={briefing}
                        audio={briefingAudio}
                        attemptId={attemptId}
                    />
                )}
            </div>

            {canReveal ? (
                <Form
                    {...WorkspaceController.revealHint.form(attemptId)}
                    options={{ preserveScroll: true }}
                >
                    {({ processing }) => (
                        <>
                            <input
                                type="hidden"
                                name="step_key"
                                value={guide.step_key}
                            />
                            <Button
                                variant="outline"
                                disabled={processing}
                                className="h-11 w-full sm:h-10 sm:w-auto"
                            >
                                Buka contoh kode
                            </Button>
                        </>
                    )}
                </Form>
            ) : null}

            <StepCards cards={guide.cards} stepKey={guide.step_key} />
        </section>
    );
}
