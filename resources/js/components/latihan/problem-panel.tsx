import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { PracticeProblem } from '@/types/latihan';

export default function ProblemPanel({
    problem,
}: {
    problem: PracticeProblem;
}) {
    return (
        <div className="space-y-4">
            <Card>
                <CardHeader>
                    <CardTitle>{problem.title ?? 'Soal'}</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                    <p className="text-sm">{problem.brief}</p>
                    <ul className="list-inside list-disc space-y-1 text-sm">
                        {problem.requirements.map((requirement) => (
                            <li key={requirement}>{requirement}</li>
                        ))}
                    </ul>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle className="text-base">
                        Tabel: {problem.table ?? '—'}
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <ul className="divide-border divide-y text-sm">
                        {problem.columns.map((column) => (
                            <li
                                key={column.name}
                                className="flex justify-between gap-4 py-2"
                            >
                                <span className="font-mono">{column.name}</span>
                                <span className="text-muted-foreground">
                                    {column.type}
                                    {column.nullable ? ' · nullable' : ''}
                                </span>
                            </li>
                        ))}
                    </ul>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle className="text-base">Kalkulasi</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4 text-sm">
                    {problem.rates.length > 0 && (
                        <ul className="space-y-1">
                            {problem.rates.map((rate) => (
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
                    )}

                    {problem.rules.map((rule) => (
                        <div key={rule.key}>
                            <p className="font-medium">{rule.description}</p>
                            <pre className="bg-muted mt-1 overflow-x-auto rounded-md p-2 font-mono text-xs">
                                {rule.key} = {rule.expression}
                            </pre>
                        </div>
                    ))}
                </CardContent>
            </Card>
        </div>
    );
}
