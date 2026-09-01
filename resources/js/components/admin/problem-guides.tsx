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
                            <span className="text-muted-foreground ml-2 text-xs font-normal">
                                {guide.cards.length} kartu
                            </span>
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        {guide.cards.map((card, index) => (
                            <div
                                key={card.title}
                                className="space-y-2 border-l-2 pl-3"
                            >
                                <p className="text-sm font-medium">
                                    {index + 1}. {card.title}
                                </p>
                                <p className="text-muted-foreground text-sm">
                                    {card.instruction}
                                </p>

                                {card.code && (
                                    <pre className="bg-muted max-h-96 overflow-auto rounded-md p-3 font-mono text-xs">
                                        {card.code}
                                    </pre>
                                )}

                                {card.note && (
                                    <p className="text-muted-foreground text-sm">
                                        Catatan: {card.note}
                                    </p>
                                )}
                            </div>
                        ))}
                    </CardContent>
                </Card>
            ))}
        </div>
    );
}
