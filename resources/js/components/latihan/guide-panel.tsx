import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { WorkspaceGuide } from '@/types/latihan';

type Props = {
    guide: WorkspaceGuide | null;
};

export default function GuidePanel({ guide }: Props) {
    if (guide === null) {
        return null;
    }

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

                {guide.tips && (
                    <p className="text-muted-foreground text-sm">
                        Tips: {guide.tips}
                    </p>
                )}
            </CardContent>
        </Card>
    );
}
