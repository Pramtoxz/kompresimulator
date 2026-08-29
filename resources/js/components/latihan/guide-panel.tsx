import { Form } from '@inertiajs/react';
import WorkspaceController from '@/actions/App/Http/Controllers/Student/WorkspaceController';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { WorkspaceGuide } from '@/types/latihan';

type Props = {
    guide: WorkspaceGuide | null;
    attemptId: number;
    guided: boolean;
};

export default function GuidePanel({ guide, attemptId, guided }: Props) {
    if (guide === null) {
        return null;
    }

    const canReveal = !guided && !guide.revealed && guide.has_example_code;

    return (
        <Card>
            <CardHeader className="pb-3">
                <CardTitle className="text-base">
                    Langkah {guide.step_no}: {guide.label}
                </CardTitle>
            </CardHeader>
            <CardContent className="space-y-3">
                <p className="text-sm">{guide.instruction}</p>

                {guide.example_code && (
                    <div className="space-y-1">
                        <p className="text-muted-foreground text-xs font-medium">
                            Contoh kode
                        </p>
                        <pre className="bg-muted max-h-72 overflow-auto rounded-md p-3 font-mono text-xs">
                            {guide.example_code}
                        </pre>
                    </div>
                )}

                {canReveal && (
                    <div className="space-y-2">
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
                        <p className="text-muted-foreground text-xs">
                            Contoh kode yang dibuka dicatat sebagai bantuan.
                        </p>
                    </div>
                )}

                {guide.tips && (
                    <p className="text-muted-foreground text-sm">
                        Tips: {guide.tips}
                    </p>
                )}
            </CardContent>
        </Card>
    );
}
