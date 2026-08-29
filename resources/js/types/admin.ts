export type FrameworkOption = {
    value: string;
    label: string;
};

export type LevelOption = {
    value: string;
    label: string;
};

export type StudentRow = {
    id: number;
    name: string;
    email: string;
    thesis_title: string | null;
    framework: string | null;
    framework_label: string | null;
    target_minutes: number;
    problems_ready: number;
    problems_queued: number;
    problems_failed: number;
    attempts: number;
};

export type StudentFormValues = {
    id: number;
    name: string;
    email: string;
    thesis_title: string | null;
    framework: string | null;
    target_minutes: number;
};

export type ProblemRow = {
    id: number;
    level: string;
    level_label: string;
    status: string;
    title: string | null;
    test_cases: number;
    guides: number;
    failure_reason: string | null;
    created_at: string | null;
};

export type ProblemColumn = {
    name: string;
    type: string;
    nullable: boolean;
};

export type CalcRule = {
    key: string;
    description: string;
    expression: string;
};

export type FormField = {
    label: string;
    name: string;
    input: 'text' | 'number' | 'date' | 'select' | 'readonly';
};

export type LookupTable = {
    key_field: string | null;
    columns: string[];
    rows: string[][];
};

export type TestCaseInput = {
    field: string;
    value: string;
};

export type ProblemTestCase = {
    label: string;
    inputs: TestCaseInput[];
    expected_total: number | null;
};

export type ProblemGuide = {
    step_no: number;
    step_key: string;
    step_label: string;
    instruction: string;
    example_code: string | null;
    tips: string | null;
};

export type ProblemReview = {
    id: number;
    student_name: string;
    student_id: number;
    level_label: string;
    framework_label: string;
    status: string;
    thesis_title: string;
    title: string | null;
    brief: string | null;
    requirements: string[];
    schema_spec: { table?: string; columns?: ProblemColumn[] };
    rules: CalcRule[];
    form_fields: FormField[];
    lookup: LookupTable;
    failure_reason: string | null;
    provider: string | null;
    model: string | null;
    test_cases: ProblemTestCase[];
    guides: ProblemGuide[];
};
