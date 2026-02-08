import { Link } from 'react-router-dom';
import { GlassCard } from '../../components/ui/GlassCard';
import { BookOpen } from 'lucide-react';

export function GuideList() {
    const guides = [
        { id: 'svs-prep', title: 'SVS準備ガイド', category: 'イベント' },
        { id: 'bear-hunt', title: '熊狩りダメージ最大化', category: '戦争' },
        { id: 'beginner-hero', title: '初心者向けおすすめ英雄', category: '初心者' },
    ];

    return (
        <div className="space-y-8">
            <h1 className="text-3xl font-orbitron font-bold text-white mb-8">攻略ガイド一覧</h1>

            <div className="space-y-4">
                {guides.map((guide, index) => (
                    <GlassCard key={guide.id} delay={index * 0.1} className="hover:border-ice-200/30 transition-colors group">
                        <Link to={`/guide/${guide.id}`} className="flex items-center gap-4">
                            <div className="p-3 bg-emerald-500/10 rounded-lg text-emerald-400">
                                <BookOpen className="w-6 h-6" />
                            </div>
                            <div className="flex-1">
                                <div className="text-xs font-mono text-emerald-400 mb-1">{guide.category}</div>
                                <h2 className="text-xl font-bold text-ice-100 group-hover:text-white transition-colors">
                                    {guide.title}
                                </h2>
                            </div>
                            <div className="px-4 py-2 rounded-full border border-ice-200/10 text-ice-200/60 text-sm group-hover:bg-ice-500/10 transition-colors">
                                読む
                            </div>
                        </Link>
                    </GlassCard>
                ))}
            </div>
        </div>
    );
}
