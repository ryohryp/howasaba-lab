import { useParams, Link } from 'react-router-dom';
import { GlassCard } from '../../components/ui/GlassCard';
import { ArrowLeft } from 'lucide-react';

export function HeroDetail() {
    const { id } = useParams();

    return (
        <div className="space-y-8">
            <Link to="/hero" className="flex items-center gap-2 text-ice-200 hover:text-white transition-colors">
                <ArrowLeft className="w-4 h-4" />
                データベースに戻る
            </Link>

            <GlassCard>
                <h1 className="text-4xl font-orbitron font-bold text-white mb-2 capitalize">{id}</h1>
                <p className="text-ice-200/60">{id} の英雄詳細がここに表示されます。</p>

                <div className="mt-8 p-4 bg-navy-900/50 rounded-lg border border-ice-200/10">
                    <p className="text-sm font-mono text-ice-200/40">
            // STATUS: データ待機中<br />
            // ACCESS: 制限中
                    </p>
                </div>
            </GlassCard>
        </div>
    );
}
