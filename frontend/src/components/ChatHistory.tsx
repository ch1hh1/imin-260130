import type { Message } from '../types/chat';
import { MessageItem } from './MessageItem';

interface ChatHistoryProps {
  messages: Message[];
}

export function ChatHistory({ messages }: ChatHistoryProps) {
  return (
    <div className="flex-1 overflow-y-auto p-4">
      {messages.length === 0 ? (
        <p className="text-slate-500 text-center py-8">
          質問を入力して送信すると、回答が表示されます。
        </p>
      ) : (
        messages.map((msg) => <MessageItem key={msg.id} message={msg} />)
      )}
    </div>
  );
}
