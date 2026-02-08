import { motion } from 'framer-motion';
import { Calculator, Calendar } from 'lucide-react';
import { GlassCard } from '../components/ui/GlassCard';

/* Assuming existing tools will be integrated later, creating placeholders */

export function Tools() {
    const tools = [
        {
            name: 'WOS-Navi',
            description: '包括的なイベントカレンダーと英雄世代スケジュール。',
            icon: Calendar,
            status: '稼働中',
            version: '1.2.0'
        },
        {
            name: '熊狩り計算機',
            description: '正確な編成計算で集結ダメージを最適化。',
            icon: Calculator,
            status: '開発中',
            version: '0.9.0'
        }
    ];

    return (
        <div className="space-y-8">
            <motion.div
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
            >
                <h1 className="text-3xl font-orbitron font-bold text-white mb-2">ツール一覧</h1>
                <p className="text-ice-200/60">ホワイトアウト・サバイバル用自作ユーティリティ。</p>
            </motion.div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {tools.map((tool, index) => (
                    <GlassCard key={tool.name} delay={index * 0.1}>
                        <div className="flex justify-between items-start mb-4">
                            <div className="p-3 bg-ice-500/10 rounded-lg text-ice-200">
                                <tool.icon className="w-6 h-6" />
                            </div>
                            <span className={`text-xs px-2 py-1 rounded-full border ${tool.status === '稼働中'
                                    ? 'border-emerald-500/30 text-emerald-400 bg-emerald-500/10'
                                    : 'border-amber-500/30 text-amber-400 bg-amber-500/10'
                                }`}>
                                {tool.status}
                            </span>
                        </div>
                        <h3 className="text-xl font-bold text-white mb-2">{tool.name}</h3>
                        <p className="text-ice-200/60 text-sm mb-4">{tool.description}</p>
                        <div className="flex justify-between items-center text-xs text-ice-200/40 font-mono">
                            <span>v{tool.version}</span>
                            <button className="bg-neon-cyan/10 hover:bg-neon-cyan/20 text-neon-cyan px-4 py-2 rounded transition-colors">
                                起動
                            </button>
                        </div>
                    </GlassCard>
                ))}
            </div>
        </div>
    );
}
