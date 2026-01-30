export type MessageRole = 'user' | 'assistant';

export interface Reference {
  id: string;
  title: string;
  category: string;
  version: string;
  revisedAt: string;
  urlOrId: string;
}

export interface Message {
  id: string;
  role: MessageRole;
  content: string;
  references: Reference[];
  timestamp: number;
}
