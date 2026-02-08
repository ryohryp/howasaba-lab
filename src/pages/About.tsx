import { motion } from 'framer-motion';
import { GlassCard } from '../components/ui/GlassCard';
import { Github, Twitter } from 'lucide-react';

export function About() {
    return (
        <div className="max-w-3xl mx-auto space-y-8">
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                className="text-center"
            >
                <h1 className="text-3xl font-orbitron font-bold text-white mb-4">サイトについて</h1>
                <div className="h-1 w-20 bg-neon-cyan mx-auto rounded-full" />
            </motion.div>

            <GlassCard className="space-y-6">
                <div>
                    <h2 className="text-xl font-bold text-ice-100 mb-4">HOWASABA LAB とは</h2>
                    <p className="text-ice-200/70 leading-relaxed">
                        このウェブサイトは、モバイルゲーム「Whiteout Survival (ホワイトアウト・サバイバル)」の攻略情報、
                        および自作ツールを公開・管理するための個人的な実験場です。
                        最新のWeb技術（React, TailwindCSS, Framer Motionなど）を用いた「Webアプリ型」の情報サイトを目指しています。
                    </p>
                </div>

                <div>
                    <h2 className="text-xl font-bold text-ice-100 mb-4">管理人</h2>
                    <div className="flex items-center gap-4">
                        <div className="w-16 h-16 rounded-full bg-ice-500/20 flex items-center justify-center text-2xl font-bold text-ice-200">
                            R
                        </div>
                        <div>
                            <h3 className="font-bold text-white">りょう (Ryo)</h3>
                            <p className="text-ice-200/60 text-sm">Frontend Developer & Gamer</p>
                            <div className="flex gap-3 mt-2">
                                <a href="#" className="text-ice-200/40 hover:text-white transition-colors"><Twitter className="w-4 h-4" /></a>
                                <a href="#" className="text-ice-200/40 hover:text-white transition-colors"><Github className="w-4 h-4" /></a>
                            </div>
                        </div>
                    </div>
                </div>
            </GlassCard>
        </div>
    );
}
