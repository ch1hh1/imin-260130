import { useState, useCallback } from 'react';
import type { Message } from './types/chat';
import { Disclaimer } from './components/Disclaimer';
import { ChatHistory } from './components/ChatHistory';
import { QuestionInput } from './components/QuestionInput';
import { useMockReply } from './hooks/useMockReply';

function generateId(): string {
  return `msg-${Date.now()}-${Math.random().toString(36).slice(2, 9)}`;
}

function App() {
  const [messages, setMessages] = useState<Message[]>([]);
  const { getMockReply, isLoading } = useMockReply();

  const handleSubmit = useCallback(
    async (text: string) => {
      const userMessage: Message = {
        id: generateId(),
        role: 'user',
        content: text,
        references: [],
        timestamp: Date.now(),
      };
      setMessages((prev) => [...prev, userMessage]);

      const reply = await getMockReply(text);
      setMessages((prev) => [...prev, reply]);
    },
    [getMockReply]
  );

  const handleNewChat = useCallback(() => {
    setMessages([]);
  }, []);

  return (
    <div className="flex flex-col h-screen bg-slate-50">
      <header className="shrink-0 border-b border-slate-200 bg-white px-4 py-3 flex items-center justify-between">
        <h1 className="text-xl font-bold text-slate-800">AIご案内係</h1>
        <button
          type="button"
          onClick={handleNewChat}
          className="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50"
        >
          新規会話
        </button>
      </header>

      <div className="shrink-0 px-4 py-2">
        <Disclaimer />
      </div>

      <ChatHistory messages={messages} />

      <div className="shrink-0">
        <QuestionInput onSubmit={handleSubmit} disabled={isLoading} />
      </div>
    </div>
  );
}

export default App;
