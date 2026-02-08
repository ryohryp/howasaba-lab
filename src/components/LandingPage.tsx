import { useState } from 'react';
import { motion } from 'framer-motion';
import { GlassCard } from './ui/GlassCard';
import { ArrowRight, Snowflake } from 'lucide-react';

export function LandingPage() {
    const [isHovered, setIsHovered] = useState(false);

    return (
        <div className="relative w-full h-screen overflow-hidden bg-navy-900 text-white flex items-center justify-center font-sans">
            {/* Background with animated gradient */}
            <div className="absolute inset-0 bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0b1120] z-0" />

            {/* Ambient Light Effects */}
            <div className="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-ice-400/10 rounded-full blur-[100px] pointer-events-none mix-blend-screen animate-pulse" />
            <div className="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-neon-cyan/5 rounded-full blur-[120px] pointer-events-none mix-blend-screen" />

            {/* Grid Pattern Overlay */}
            <div className="absolute inset-0 opacity-[0.03] z-0 pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]" />

            <div className="z-10 w-full max-w-md px-6 relative">
                <GlassCard className="text-center space-y-8 border-ice-200/10" delay={0.2}>

                    {/* Header Section */}
                    <motion.div
                        initial={{ scale: 0.9, opacity: 0 }}
                        animate={{ scale: 1, opacity: 1 }}
                        transition={{ duration: 0.8, ease: "easeOut" }}
                        className="flex flex-col items-center"
                    >
                        <div className="relative w-20 h-20 mb-6 group cursor-pointer">
                            <div className="absolute inset-0 bg-ice-400/20 rounded-full blur-xl group-hover:bg-ice-400/30 transition-all duration-500" />
                            <div className="relative w-full h-full bg-gradient-to-tr from-white/10 to-transparent rounded-full flex items-center justify-center border border-ice-200/30 backdrop-blur-sm shadow-[0_0_30px_rgba(56,189,248,0.2)]">
                                <Snowflake className="w-8 h-8 text-ice-200 animate-[spin_10s_linear_infinite]" />
                            </div>
                        </div>

                        <h1 className="text-4xl md:text-5xl font-orbitron font-bold tracking-widest text-transparent bg-clip-text bg-gradient-to-b from-white via-ice-200 to-ice-400 drop-shadow-[0_2px_10px_rgba(56,189,248,0.5)]">
                            HOWASABA<br />LAB
                        </h1>
                        <div className="h-px w-24 bg-gradient-to-r from-transparent via-ice-200/50 to-transparent my-4" />
                        <p className="text-ice-200/60 text-xs font-light tracking-[0.3em] uppercase">
                            Secure Research Facility
                        </p>
                    </motion.div>

                    {/* Action Button */}
                    <motion.button
                        whileHover={{ scale: 1.02 }}
                        whileTap={{ scale: 0.98 }}
                        onHoverStart={() => setIsHovered(true)}
                        onHoverEnd={() => setIsHovered(false)}
                        className="w-full group relative overflow-hidden rounded-xl p-[1px] shadow-[0_0_20px_rgba(34,211,238,0.1)] hover:shadow-[0_0_30px_rgba(34,211,238,0.3)] transition-shadow duration-300"
                        onClick={() => console.log("Enter System")} // Placeholder for future navigation
                    >
                        <div className="absolute inset-0 bg-gradient-to-r from-ice-400 to-neon-cyan opacity-80" />
                        <div className="relative bg-navy-900/80 hover:bg-navy-900/40 backdrop-blur-sm rounded-xl py-4 flex items-center justify-center gap-3 transition-colors duration-300">
                            <span className="font-orbitron font-bold tracking-widest text-ice-100 group-hover:text-white transition-colors text-lg">
                                ENTER SYSTEM
                            </span>
                            <ArrowRight className="w-5 h-5 text-ice-200 group-hover:text-white group-hover:translate-x-1 transition-all duration-300" />
                        </div>
                    </motion.button>

                    <div className="flex items-center justify-center gap-2 text-[10px] text-ice-200/30 font-mono">
                        <div className={`w-1.5 h-1.5 rounded-full ${isHovered ? 'bg-neon-cyan shadow-[0_0_5px_#22d3ee]' : 'bg-emerald-500/50'} transition-colors duration-300`} />
                        SYSTEM: ONLINE // PUBLIC ACCESS
                    </div>
                </GlassCard>

                {/* Footer Version Info */}
                <div className="absolute -bottom-16 w-full text-center">
                    <p className="text-[10px] text-ice-200/20 font-mono tracking-widest">
                        v2.0.4 // HOWASABA LABS
                    </p>
                </div>
            </div>
        </div>
    );
}
