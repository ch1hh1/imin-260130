import type { Reference } from '../types/chat';

interface ReferencesListProps {
  references: Reference[];
}

export function ReferencesList({ references }: ReferencesListProps) {
  if (references.length === 0) return null;

  return (
    <div className="mt-3 pl-4 border-l-2 border-slate-300">
      <p className="text-xs font-semibold text-slate-600 mb-2">参照元</p>
      <ul className="space-y-2 text-sm">
        {references.map((ref) => (
          <li key={ref.id} className="text-slate-700">
            <span className="font-medium">{ref.title}</span>
            <span className="text-slate-500 ml-2">
              （{ref.category} / 版{ref.version} / 改定日: {ref.revisedAt} / ID: {ref.urlOrId}）
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}
