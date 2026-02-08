import { Link, useLocation } from 'react-router-dom';
import { Database, FileText, Search, Wrench, Snowflake, Menu, X } from 'lucide-react';
import { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

export function Header() {
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const location = useLocation();

    const navItems = [
        { name: 'ツール', path: '/tools', icon: Wrench },
        { name: 'データベース', path: '/hero', icon: Database },
        { name: '攻略ガイド', path: '/guide', icon: FileText },
    ];

    const isActive = (path: string) => {
        if (path === '/') return location.pathname === '/';
        return location.pathname.startsWith(path);
    };

    return (
        <header className="fixed top-0 left-0 right-0 z-50 bg-navy-900/80 backdrop-blur-md border-b border-ice-200/10 h-16">
            <div className="container mx-auto px-4 h-full flex items-center justify-between">
                {/* Logo */}
                <Link to="/" className="flex items-center gap-2 group">
                    <div className="relative w-8 h-8 flex items-center justify-center">
                        <div className="absolute inset-0 bg-ice-400/20 rounded-full blur-md group-hover:bg-ice-400/30 transition-all duration-300" />
                        <Snowflake className="w-5 h-5 text-ice-200 animate-[spin_10s_linear_infinite]" />
                    </div>
                    <span className="font-orbitron font-bold text-lg text-transparent bg-clip-text bg-gradient-to-r from-white to-ice-200 tracking-wider">
                        HOWASABA
                    </span>
                </Link>

                {/* Desktop Navigation */}
                <nav className="hidden md:flex items-center gap-1">
                    {navItems.map((item) => (
                        <Link
                            key={item.path}
                            to={item.path}
                            className={`relative px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-300 group
                ${isActive(item.path)
                                    ? 'text-ice-100 bg-ice-500/10'
                                    : 'text-ice-200/60 hover:text-ice-100 hover:bg-ice-500/5'
                                }`}
                        >
                            <item.icon className={`w-4 h-4 ${isActive(item.path) ? 'text-neon-cyan' : 'group-hover:text-neon-cyan'} transition-colors`} />
                            <span className="font-medium text-sm">{item.name}</span>
                            {isActive(item.path) && (
                                <motion.div
                                    layoutId="navbar-indicator"
                                    className="absolute bottom-0 left-2 right-2 h-0.5 bg-neon-cyan shadow-[0_0_10px_#22d3ee]"
                                />
                            )}
                        </Link>
                    ))}
                    <button className="ml-2 p-2 text-ice-200/60 hover:text-neon-cyan transition-colors">
                        <Search className="w-5 h-5" />
                    </button>
                </nav>

                {/* Mobile Menu Button */}
                <button
                    className="md:hidden p-2 text-ice-200"
                    onClick={() => setIsMenuOpen(!isMenuOpen)}
                >
                    {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                </button>
            </div>

            {/* Mobile Navigation */}
            <AnimatePresence>
                {isMenuOpen && (
                    <motion.div
                        initial={{ opacity: 0, y: -20 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -20 }}
                        className="md:hidden absolute top-16 left-0 right-0 bg-navy-900/95 backdrop-blur-lg border-b border-ice-200/10 p-4"
                    >
                        <div className="flex flex-col gap-2">
                            {navItems.map((item) => (
                                <Link
                                    key={item.path}
                                    to={item.path}
                                    onClick={() => setIsMenuOpen(false)}
                                    className={`flex items-center gap-3 p-3 rounded-lg transition-colors
                    ${isActive(item.path)
                                            ? 'bg-ice-500/10 text-ice-100'
                                            : 'text-ice-200/60 hover:bg-ice-500/5 hover:text-ice-100'
                                        }`}
                                >
                                    <item.icon className="w-5 h-5" />
                                    <span className="font-medium">{item.name}</span>
                                </Link>
                            ))}
                            <button className="flex items-center gap-3 p-3 text-ice-200/60 hover:text-ice-100 transition-colors">
                                <Search className="w-5 h-5" />
                                <span className="font-medium">検索</span>
                            </button>
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </header>
    );
}
