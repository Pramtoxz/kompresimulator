import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { CalcRule, RateRow } from '@/types/admin';

type Props = {
    rules: CalcRule[];
    rates: RateRow[];
};

export default function ProblemRulesCard({ rules, rates }: Props) {
    return (
        <Card>
            <CardHeader>
                <CardTitle>Aturan kalkulasi</CardTitle>
            </CardHeader>
            <CardContent className="space-y-5">
                {rates.length > 0 && (
                    <div className="space-y-2">
                        <p className="text-muted-foreground text-xs font-medium uppercase">
                            Tarif
                        </p>
                        <ul className="space-y-1 text-sm">
                            {rates.map((rate) => (
                                <li
                                    key={`${rate.key}-${rate.option}`}
                                    className="flex justify-between gap-4"
                                >
                                    <span className="font-mono">
                                        {rate.key} · {rate.option}
                                    </span>
                                    <span>
                                        {new Intl.NumberFormat('id-ID').format(
                                            rate.amount,
                                        )}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    </div>
                )}

                <div className="space-y-3">
                    {rules.map((rule) => (
                        <div key={rule.key} className="space-y-1">
                            <p className="font-mono text-sm font-medium">
                                {rule.key}
                            </p>
                            <p className="text-muted-foreground text-sm">
                                {rule.description}
                            </p>
                            <pre className="bg-muted overflow-x-auto rounded-md p-2 font-mono text-xs">
                                {rule.expression}
                            </pre>
                        </div>
                    ))}
                </div>
            </CardContent>
        </Card>
    );
}
