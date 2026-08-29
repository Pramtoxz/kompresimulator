export type PracticeStep = {
    step_no: number;
    step_key: string;
    label: string;
    status: 'pending' | 'in_progress' | 'done';
    duration_seconds: number | null;
};

export type PracticeAttempt = {
    id: number;
    status: string;
    current_step: number;
    target_minutes: number;
    started_at: string;
    duration_seconds: number | null;
    duration_source: string | null;
    steps: PracticeStep[];
};

export type PracticeColumn = {
    name: string;
    type: string;
    nullable: boolean;
};

export type PracticeRule = {
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

export type PracticeProblem = {
    title: string | null;
    brief: string | null;
    requirements: string[];
    table: string | null;
    columns: PracticeColumn[];
    rules: PracticeRule[];
    form_fields: FormField[];
    lookup: LookupTable;
};

export type PracticeHistoryRow = {
    id: number;
    title: string | null;
    level_label: string;
    duration_minutes: number | null;
    target_minutes: number;
    within_target: boolean;
    finished_at: string | null;
};

export type PracticeSummary = {
    levels: PracticeLevel[];
    running: PracticeRunning | null;
    history: PracticeHistoryRow[];
};

export type WorkspaceFile = {
    path: string;
    step_key: string | null;
    language: string;
    content: string;
};

export type WorkspaceGuide = {
    step_no: number;
    step_key: string;
    label: string;
    instruction: string;
    example_code: string | null;
    has_example_code: boolean;
    revealed: boolean;
    tips: string | null;
};

export type TestCaseInput = {
    field: string;
    value: string;
};

export type WorkspaceTestCase = {
    id: number;
    label: string;
    inputs: TestCaseInput[];
};

export type WorkspaceCheck = {
    kind: string;
    passed: boolean;
    message: string | null;
};

export type WorkspaceDatabase = {
    table: string | null;
    columns: string[];
    rows: Record<string, unknown>[];
};

export type PracticeLevel = {
    value: string;
    label: string;
    description: string;
    problem_id: number | null;
};

export type PracticeRunning = {
    id: number;
    level: string;
    level_label: string;
};
