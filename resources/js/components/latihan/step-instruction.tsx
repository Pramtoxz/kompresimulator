import { Form } from '@inertiajs/react';
import WorkspaceController from '@/actions/App/Http/Controllers/Student/WorkspaceController';
import { Button } from '@/components/ui/button';
import type { WorkspaceGuide } from '@/types/latihan';

type Props = {
    guide: WorkspaceGuide | null;
    attemptId: number;
    guided: boolean;
};

export default function StepInstruction({
    guide,
    attemptId,
    guided,
}: Props) {
    if (guide === null) {
        return null;
    }

    const canReveal = !guided && !guide.revealed && guide.has_example_code;

    return (
        <section className="space-y-3">
            <h1 className="text-xl font-semibold tracking-tight">
                Langkah {guide.step_no} — {guide.label}
            </h1>

            <p className="text-sm leading-relaxed">{guide.instruction}</p>

            {guide.example_code && (
                <pre className="bg-muted max-h-80 overflow-auto rounded-lg p-3 font-mono text-xs">
                    {guide.example_code}
                </pre>
            )}

            {canReveal && (
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
                                size="sm"
                                disabled={processing}
                                className="h-10"
                            >
                                Buka contoh kode
                            </Button>
                        </>
                    )}
                </Form>
            )}

            {guide.tips && (
                <p className="text-muted-foreground border-l-2 pl-3 text-sm">
                    {guide.tips}
                </p>
            )}
        </section>
    );
}
