import { useState, useCallback } from 'react';

const MAX_LENGTH = 4000;

interface QuestionInputProps {
  onSubmit: (text: string) => void;
  disabled?: boolean;
}

export function QuestionInput({ onSubmit, disabled }: QuestionInputProps) {
  const [value, setValue] = useState('');

  const handleSubmit = useCallback(
    (e: React.FormEvent) => {
      e.preventDefault();
      const trimmed = value.trim();
      if (!trimmed || disabled) return;
      onSubmit(trimmed);
      setValue('');
    },
    [value, disabled, onSubmit]
  );

  const handleChange = useCallback((e: React.ChangeEvent<HTMLTextAreaElement>) => {
    const v = e.target.value;
    if (v.length <= MAX_LENGTH) setValue(v);
  }, []);

  const canSubmit = value.trim().length > 0 && !disabled;

  return (
    <form onSubmit={handleSubmit} className="border-t border-slate-200 bg-white p-4">
      <div className="flex flex-col gap-2">
        <textarea
          value={value}
          onChange={handleChange}
          placeholder="質問を入力してください"
          rows={3}
          maxLength={MAX_LENGTH}
          disabled={disabled}
          className="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:bg-slate-100 disabled:text-slate-500"
        />
        <div className="flex items-center justify-between">
          <span className="text-sm text-slate-500">
            {value.length} / {MAX_LENGTH}
          </span>
          <button
            type="submit"
            disabled={!canSubmit}
            className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed"
          >
            送信
          </button>
        </div>
      </div>
    </form>
  );
}
