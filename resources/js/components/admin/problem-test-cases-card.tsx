import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { ProblemTestCase } from '@/types/admin';

export default function ProblemTestCasesCard({
    testCases,
}: {
    testCases: ProblemTestCase[];
}) {
    return (
        <Card>
            <CardHeader>
                <CardTitle>Test case</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
                {testCases.map((testCase) => (
                    <div key={testCase.label} className="space-y-1">
                        <p className="text-sm font-medium">{testCase.label}</p>
                        <p className="text-muted-foreground font-mono text-xs">
                            {testCase.inputs
                                .map((input) => `${input.field}=${input.value}`)
                                .join(', ')}
                        </p>
                        <p className="text-sm">
                            Total:{' '}
                            <span className="font-medium">
                                {testCase.expected_total === null
                                    ? '—'
                                    : new Intl.NumberFormat('id-ID').format(
                                          testCase.expected_total,
                                      )}
                            </span>
                        </p>
                    </div>
                ))}
            </CardContent>
        </Card>
    );
}
