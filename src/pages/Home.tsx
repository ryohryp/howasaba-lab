import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { Wrench, FileText, ArrowRight, Shield, Swords } from 'lucide-react';
import { GlassCard } from '../components/ui/GlassCard';

export function Home() {
    const features = [
        {
            title: 'ツール',
            description: 'WOS-Navi、熊狩り計算機、その他の自作ツールへのアクセス。',
            icon: Wrench,
            path: '/tools',
            color: 'text-neon-cyan',
            bg: 'bg-neon-cyan/10'
        },
        {
            title: '英雄データベース',
            description: '英雄スキル、世代、最適ビルドの詳細分析。',
            icon: Shield,
            path: '/hero',
            color: 'text-ice-200',
            bg: 'bg-ice-200/10'
        },
        {
            title: '攻略ガイド',
            description: 'イベント、戦争、内政に関する包括的なガイド。',
            icon: FileText,
            path: '/guide',
            color: 'text-emerald-400',
            bg: 'bg-emerald-400/10'
        }
    ];

    return (
        <div className="space-y-12">
            <section className="text-center space-y-4 pt-10">
                <motion.h1
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="text-4xl md:text-6xl font-orbitron font-bold text-transparent bg-clip-text bg-gradient-to-r from-white via-ice-200 to-ice-400"
                >
                    COMMAND CENTER
                </motion.h1>
                <motion.p
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 0.2 }}
                    className="text-ice-200/60 text-lg max-w-2xl mx-auto"
                >
                    ホワイトアウト・サバイバルの分析、ツール、戦略情報の統合ハブ。
                </motion.p>
            </section>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {features.map((feature, index) => (
                    <GlassCard key={feature.title} delay={0.1 * index} className="group hover:border-ice-200/30 transition-colors">
                        <Link to={feature.path} className="flex flex-col h-full">
                            <div className={`w-12 h-12 rounded-xl ${feature.bg} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform`}>
                                <feature.icon className={`w-6 h-6 ${feature.color}`} />
                            </div>
                            <h3 className="text-xl font-bold text-ice-100 mb-2 group-hover:text-white transition-colors">
                                {feature.title}
                            </h3>
                            <p className="text-ice-200/60 text-sm mb-6 flex-1">
                                {feature.description}
                            </p>
                            <div className="flex items-center text-sm font-medium text-ice-200 group-hover:text-neon-cyan transition-colors mt-auto">
                                <span>モジュールへアクセス</span>
                                <ArrowRight className="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" />
                            </div>
                        </Link>
                    </GlassCard>
                ))}
            </div>

            <section>
                <div className="flex items-center justify-between mb-6">
                    <h2 className="text-2xl font-bold text-ice-100 flex items-center gap-2">
                        <Swords className="w-6 h-6 text-ice-400" />
                        最新情報
                    </h2>
                    <Link to="/guide" className="text-sm text-neon-cyan hover:underline">すべて見る</Link>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {[1, 2].map((i) => (
                        <GlassCard key={i} className="p-4 hover:bg-white/5 cursor-pointer">
                            <div className="flex justify-between items-start mb-2">
                                <span className="text-xs font-mono text-neon-cyan bg-neon-cyan/10 px-2 py-1 rounded">GUIDE</span>
                                <span className="text-xs text-ice-200/40">2026.02.08</span>
                            </div>
                            <h3 className="font-bold text-lg text-ice-100 mb-1">SVS準備ガイド v2.0</h3>
                            <p className="text-sm text-ice-200/60 line-clamp-2">準準備フェーズのタスク、ポイント計算、貢献度を最大化するための最適戦略の完全解説。</p>
                        </GlassCard>
                    ))}
                </div>
            </section>
        </div>
    );
}
