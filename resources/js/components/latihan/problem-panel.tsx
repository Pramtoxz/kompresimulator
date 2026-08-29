import ExamSheet from '@/components/soal/exam-sheet';
import type { PracticeProblem } from '@/types/latihan';

export default function ProblemPanel({
    problem,
}: {
    problem: PracticeProblem;
}) {
    return (
        <ExamSheet
            title={problem.title}
            brief={problem.brief}
            requirements={problem.requirements}
            formFields={problem.form_fields}
            lookup={problem.lookup}
            rules={problem.rules}
            table={problem.table}
        />
    );
}
