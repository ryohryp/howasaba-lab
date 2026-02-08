import { Link } from 'react-router-dom';
import { GlassCard } from '../../components/ui/GlassCard';
import { Shield, Swords, Crosshair } from 'lucide-react';

export function HeroList() {
    const heroes = [
        { id: 'molly', name: 'モリー', type: 'Lancer', gen: 1 },
        { id: 'flint', name: 'フリント', type: 'Infantry', gen: 2 },
        { id: 'alonso', name: 'アロンソ', type: 'Marksman', gen: 2 },
    ];

    return (
        <div className="space-y-8">
            <h1 className="text-3xl font-orbitron font-bold text-white mb-8">英雄データベース</h1>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {heroes.map((hero, index) => (
                    <GlassCard key={hero.id} delay={index * 0.1} className="hover:bg-white/5 transition-colors group">
                        <Link to={`/hero/${hero.id}`} className="block h-full">
                            <div className="flex justify-between items-start mb-4">
                                <span className="text-xs font-mono text-neon-cyan bg-neon-cyan/10 px-2 py-1 rounded">第{hero.gen}世代</span>
                                {hero.type === 'Infantry' && <Shield className="w-5 h-5 text-amber-500" />}
                                {hero.type === 'Lancer' && <Swords className="w-5 h-5 text-rose-500" />}
                                {hero.type === 'Marksman' && <Crosshair className="w-5 h-5 text-emerald-500" />}
                            </div>
                            <h2 className="text-xl font-bold text-ice-100 group-hover:text-white transition-colors">
                                {hero.name}
                            </h2>
                            <div className="mt-4 flex items-center text-sm text-ice-200/40 opacity-0 group-hover:opacity-100 transition-opacity">
                                詳細を見る &rarr;
                            </div>
                        </Link>
                    </GlassCard>
                ))}
            </div>
        </div>
    );
}
