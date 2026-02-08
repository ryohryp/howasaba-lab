import { Outlet } from 'react-router-dom';
import { Header } from '../components/layout/Header';
import { Footer } from '../components/layout/Footer';

export function Layout() {
    return (
        <div className="min-h-screen flex flex-col bg-navy-950 font-sans text-white">
            {/* Global Background */}
            <div className="fixed inset-0 bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0b1120] -z-20" />
            <div className="fixed inset-0 opacity-[0.03] pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')] -z-10" />

            {/* Ambient Lights */}
            <div className="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-ice-400/5 rounded-full blur-[100px] pointer-events-none mix-blend-screen -z-10" />
            <div className="fixed bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-neon-cyan/5 rounded-full blur-[120px] pointer-events-none mix-blend-screen -z-10" />

            <Header />

            <main className="flex-1 container mx-auto px-4 pt-24 pb-12 w-full max-w-7xl">
                <Outlet />
            </main>

            <Footer />
        </div>
    );
}
