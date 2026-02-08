import { useParams, Link } from 'react-router-dom';
import { GlassCard } from '../../components/ui/GlassCard';
import { ArrowLeft } from 'lucide-react';

export function GuideDetail() {
    const { id } = useParams();

    return (
        <div className="space-y-8">
            <Link to="/guide" className="flex items-center gap-2 text-ice-200 hover:text-white transition-colors">
                <ArrowLeft className="w-4 h-4" />
                ガイド一覧に戻る
            </Link>

            <GlassCard>
                <h1 className="text-3xl font-orbitron font-bold text-white mb-4 capitalize">{id?.replace(/-/g, ' ')}</h1>

                <div className="prose prose-invert max-w-none">
                    <p className="text-ice-200/80">
                        <strong>{id}</strong> のガイド内容がここに表示されます。
                    </p>
                    <hr className="border-ice-200/10 my-8" />
                    <p className="text-ice-200/60">
                        ここに詳細なテキストが入ります。
                    </p>
                </div>
            </GlassCard>
        </div>
    );
}
