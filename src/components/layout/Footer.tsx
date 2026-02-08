import { Link } from 'react-router-dom';

export function Footer() {
    return (
        <footer className="w-full bg-navy-900 border-t border-ice-200/10 py-8 mt-auto">
            <div className="container mx-auto px-4 text-center">
                <div className="flex flex-col md:flex-row justify-center items-center gap-6 mb-8 text-sm text-ice-200/60">
                    <Link to="/privacy" className="hover:text-neon-cyan transition-colors">プライバシーポリシー</Link>
                    <Link to="/about" className="hover:text-neon-cyan transition-colors">サイトについて</Link>
                    <Link to="/contact" className="hover:text-neon-cyan transition-colors">お問い合わせ</Link>
                </div>

                <div className="text-xs text-ice-200/40 font-mono tracking-widest">
                    <p>&copy; {new Date().getFullYear()} HOWASABA LAB. All rights reserved.</p>
                    <p className="mt-2">Designed by RYO</p>
                </div>
            </div>
        </footer>
    );
}
