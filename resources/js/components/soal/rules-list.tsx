import type { PracticeRule } from '@/types/latihan';

export default function RulesList({
    rules,
    showExpression = false,
}: {
    rules: PracticeRule[];
    showExpression?: boolean;
}) {
    return (
        <ol className="space-y-2 text-sm">
            {rules.map((rule) => (
                <li key={rule.key} className="space-y-1">
                    <p>{rule.description}</p>
                    {showExpression && (
                        <pre className="bg-muted overflow-x-auto rounded-md p-2 font-mono text-xs">
                            {rule.key} = {rule.expression}
                        </pre>
                    )}
                </li>
            ))}
        </ol>
    );
}
