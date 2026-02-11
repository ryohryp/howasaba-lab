<?php get_header(); ?>

<div id="snow-container"></div>

<main class="min-h-screen bg-[var(--bg-frost)] text-[var(--text-snow)] relative overflow-hidden">
    
    <!-- Hero Section -->
    <section class="relative h-[60vh] flex items-center justify-center p-8 bg-gradient-to-b from-[#1e293b] to-[var(--bg-frost)]">
        <div class="absolute inset-0 bg-[url('https://placehold.co/1920x1080/0f172a/ffffff?text=Frost+Background')] bg-cover opacity-20"></div>
        <div class="relative z-10 text-center space-y-4 max-w-4xl mx-auto">
            <h1 class="text-6xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200 drop-shadow-[0_0_10px_rgba(255,255,255,0.5)]">
                WHITE OUT SURVIVAL
                <span class="block text-2xl font-light text-blue-300 mt-2 tracking-widest">STRATEGY GUIDE</span>
            </h1>
            <p class="text-lg text-blue-100/80 max-w-2xl mx-auto">
                Survive the eternal winter. Master the strategy. Dominate the frost.
            </p>
            <div class="flex gap-4 justify-center mt-8">
                <a href="#events" class="px-8 py-3 rounded-full bg-[var(--accent-fire-crystal)] hover:bg-orange-600 text-white font-bold transition-all shadow-[0_0_20px_rgba(255,69,0,0.4)] hover:shadow-[0_0_30px_rgba(255,69,0,0.6)] transform hover:scale-105">
                    Viewing Events
                </a>
                <a href="#heroes" class="px-8 py-3 rounded-full glass-panel hover:bg-white/10 text-white font-bold transition-all">
                    Hero Database
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Events (Mockup Data since no DB entries yet) -->
    <section id="events" class="py-16 container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold border-l-4 border-[var(--accent-fire-crystal)] pl-4">Current Events</h2>
            <a href="/events" class="text-blue-300 hover:text-white transition-colors">View All &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            // Mock Loop for Events
            $events = [
                ['title' => 'King of Icefield', 'date' => '2023-11-01', 'duration' => '3 Days', 'desc' => 'Battle for the throne of the Icefield.'],
                ['title' => 'Foundry Battle', 'date' => '2023-11-05', 'duration' => '1 Day', 'desc' => 'Legion vs Legion strategic combat.'],
                ['title' => 'Crazy Joe', 'date' => '2023-11-10', 'duration' => '2 Days', 'desc' => 'Defend your city against waves of bandits.'],
            ];
            foreach($events as $evt): ?>
            <div class="glass-panel p-6 hover:translate-y-[-5px] transition-transform duration-300 group cursor-pointer relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-500/5 group-hover:bg-blue-500/10 transition-colors"></div>
                <div class="relative z-10">
                    <span class="inline-block px-3 py-1 rounded bg-blue-900/50 text-xs text-blue-200 mb-3 border border-blue-500/30">
                        Starting: <?php echo $evt['date']; ?>
                    </span>
                    <h3 class="text-xl font-bold mb-2 group-hover:text-blue-300 transition-colors"><?php echo $evt['title']; ?></h3>
                    <p class="text-sm text-gray-400 mb-4"><?php echo $evt['desc']; ?></p>
                    <div class="flex items-center text-xs text-blue-200/60">
                        <span class="mr-3">🕒 <?php echo $evt['duration']; ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Top Heroes (Mockup) -->
    <section id="heroes" class="py-16 bg-[#0b1120]/50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Top Tier Heroes</h2>
            
            <div x-data="{ activeTab: 'gen1' }" class="max-w-6xl mx-auto">
                <!-- Filters -->
                <div class="flex justify-center gap-4 mb-10">
                    <?php foreach(['gen1' => 'Generation 1', 'gen2' => 'Generation 2', 'gen3' => 'Generation 3'] as $key => $label): ?>
                    <button @click="activeTab = '<?php echo $key; ?>'" 
                        :class="activeTab === '<?php echo $key; ?>' ? 'bg-blue-600/50 border-blue-400' : 'bg-transparent border-transparent hover:bg-white/5'"
                        class="px-6 py-2 rounded-lg border transition-all text-sm font-semibold tracking-wide">
                        <?php echo $label; ?>
                    </button>
                    <?php endforeach; ?>
                </div>

                <!-- Hero Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Standard Repeat for Mockup -->
                    <!-- In reality, this would be a WP Query loop filtering by meta key 'hero_gen' -->
                     <?php for($i=1; $i<=4; $i++): ?>
                    <div class="glass-panel p-4 text-center group relative overflow-hidden">
                        <div class="w-24 h-24 mx-auto rounded-full bg-gray-700 mb-4 border-2 border-dashed border-gray-600 group-hover:border-blue-400 transition-colors flex items-center justify-center text-xs text-gray-500">
                            Hero Img
                        </div>
                        <h3 class="font-bold text-lg mb-1">Molly</h3>
                        <p class="text-xs text-[var(--accent-fire-crystal)] font-bold uppercase tracking-wider mb-2">Lancer</p>
                        <div class="text-xs text-gray-400 bg-black/20 rounded p-2 mt-2">
                             Exploration: <span class="text-green-400">S Tier</span>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </section>

</main>

<footer class="py-8 text-center text-gray-500 text-sm border-t border-[var(--glass-border)] bg-[#0b1120]">
    <p>&copy; <?php echo date('Y'); ?> GlacialCore Theme. Unofficial Whiteout Survival Fan Site.</p>
</footer>

<?php get_footer(); ?>
