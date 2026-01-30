import { useCallback, useState } from 'react';
import type { Message, Reference } from '../types/chat';

const MOCK_DELAY_MS = 1500;

const MOCK_REFERENCES: Reference[] = [
  {
    id: 'ref-001',
    title: '総務FAQ：有給休暇の取得手続き',
    category: '人事・勤怠',
    version: '1.2',
    revisedAt: '2025-01-15',
    urlOrId: 'KB-001',
  },
  {
    id: 'ref-002',
    title: '社内規定：休暇申請の流れ',
    category: '規程',
    version: '2.0',
    revisedAt: '2024-12-01',
    urlOrId: 'KB-002',
  },
  {
    id: 'ref-003',
    title: '業務マニュアル：申請書の書き方',
    category: 'マニュアル',
    version: '1.0',
    revisedAt: '2025-01-10',
    urlOrId: 'KB-003',
  },
];

const MOCK_ANSWERS = [
  'ご質問ありがとうございます。有給休暇の取得については、事前に申請書の提出が必要です。申請書は総務窓口または社内ポータルからダウンロードできます。取得希望日の2週間前までにご提出ください。',
  '承知しました。社内規定に基づき、休暇申請は所定の申請書に必要事項を記入のうえ、上長の承認を得た上で総務へ提出してください。詳細は参照元のナレッジをご確認ください。',
  'お問い合わせの件、参照したナレッジに基づき回答いたします。不明点がございましたら、追加でお尋ねください。',
];

function generateId(): string {
  return `msg-${Date.now()}-${Math.random().toString(36).slice(2, 9)}`;
}

export function useMockReply() {
  const [isLoading, setIsLoading] = useState(false);

  const getMockReply = useCallback(async (question: string): Promise<Message> => {
    setIsLoading(true);
    await new Promise((resolve) => setTimeout(resolve, MOCK_DELAY_MS));
    setIsLoading(false);

    const answerIndex = Math.floor(Math.random() * MOCK_ANSWERS.length);
    const refs = MOCK_REFERENCES.slice(0, 2 + Math.floor(Math.random() * 2));

    return {
      id: generateId(),
      role: 'assistant',
      content: MOCK_ANSWERS[answerIndex],
      references: refs,
      timestamp: Date.now(),
    };
  }, []);

  return { getMockReply, isLoading };
}
