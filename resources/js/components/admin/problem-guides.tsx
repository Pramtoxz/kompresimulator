import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { ProblemGuide } from '@/types/admin';

export default function ProblemGuides({
    guides,
}: {
    guides: ProblemGuide[];
}) {
    return (
        <div className="space-y-4">
            {guides.map((guide) => (
                <Card key={guide.step_key}>
                    <CardHeader>
                        <CardTitle className="text-base">
                            {guide.step_no}. {guide.step_label}
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        <p className="text-sm">{guide.instruction}</p>

                        {guide.example_code && (
                            <pre className="bg-muted max-h-96 overflow-auto rounded-md p-3 font-mono text-xs">
                                {guide.example_code}
                            </pre>
                        )}

                        {guide.tips && (
                            <p className="text-muted-foreground text-sm">
                                Tips: {guide.tips}
                            </p>
                        )}
                    </CardContent>
                </Card>
            ))}
        </div>
    );
}
