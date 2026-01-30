import type { Message } from '../types/chat';
import { ReferencesList } from './ReferencesList';

interface MessageItemProps {
  message: Message;
}

export function MessageItem({ message }: MessageItemProps) {
  const isUser = message.role === 'user';

  return (
    <div
      className={`flex ${isUser ? 'justify-end' : 'justify-start'} mb-4`}
      data-testid="message-item"
    >
      <div
        className={`max-w-[85%] rounded-lg px-4 py-3 ${
          isUser
            ? 'bg-blue-600 text-white'
            : 'bg-slate-100 text-slate-800 border border-slate-200'
        }`}
      >
        <p className="whitespace-pre-wrap break-words">{message.content}</p>
        {!isUser && message.references.length > 0 && (
          <ReferencesList references={message.references} />
        )}
      </div>
    </div>
  );
}
