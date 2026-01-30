<?php

return [
    'question_max_length' => (int) env('AI_ANNAI_QUESTION_MAX_LENGTH', 4000),
    'session_timeout_minutes' => (int) env('AI_ANNAI_SESSION_TIMEOUT_MINUTES', 30),
    'disclaimer' => env('AI_ANNAI_DISCLAIMER', '回答は参考情報であり、最終判断は利用者が行うものとします。'),
    'show_sources' => env('AI_ANNAI_SHOW_SOURCES', true),
    'log_retention_days' => (int) env('AI_ANNAI_LOG_RETENTION_DAYS', 90),
];
