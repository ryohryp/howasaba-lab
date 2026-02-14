<?php
/**
 * Server Age Calculator Part
 * Matches the logic in assets/js/server-age.js
 */
?>
<div x-data="serverAgeCalculator" class="rounded-xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
    <h3 class="mb-4 text-lg font-bold text-ice-blue flex items-center gap-2">
        <span class="text-2xl">📅</span> 
        <?php _e('Server Age & Gen Check', 'wos-frost-fire'); ?>
    </h3>

    <div class="mb-4 flex gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" value="date" x-model="inputMode" class="accent-fire-crystal">
            <span class="text-sm text-gray-300"><?php _e('By Date', 'wos-frost-fire'); ?></span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" value="server" x-model="inputMode" class="accent-fire-crystal">
            <span class="text-sm text-gray-300"><?php _e('By Server #', 'wos-frost-fire'); ?></span>
        </label>
    </div>

    <!-- Date Input -->
    <div x-show="inputMode === 'date'" class="space-y-2">
        <label class="block text-xs uppercase tracking-wide text-gray-400"><?php _e('Server Open Date', 'wos-frost-fire'); ?></label>
        <input type="date" x-model="serverDate" @change="calculate" 
            class="w-full rounded bg-black/20 px-4 py-2 text-white border border-white/10 focus:border-ice-blue focus:outline-none focus:ring-1 focus:ring-ice-blue transition-all">
    </div>

    <!-- Server Number Input -->
    <div x-show="inputMode === 'server'" class="space-y-2" style="display: none;">
        <label class="block text-xs uppercase tracking-wide text-gray-400"><?php _e('Server Number', 'wos-frost-fire'); ?></label>
        <input type="number" x-model="serverNumber" @input="calculate" placeholder="e.g. 1042"
            class="w-full rounded bg-black/20 px-4 py-2 text-white border border-white/10 focus:border-ice-blue focus:outline-none focus:ring-1 focus:ring-ice-blue transition-all">
        <p class="text-xs text-yellow-400 mt-1">
            *<?php _e('Server number lookup is experimental.', 'wos-frost-fire'); ?>
        </p>
    </div>

    <!-- Results -->
    <div x-show="serverAge !== null" class="mt-6 border-t border-white/10 pt-4 animate-fade-in" style="display: none;">
        <div class="grid grid-cols-2 gap-4 text-center">
            <div>
                <div class="text-xs text-gray-400"><?php _e('Server Age', 'wos-frost-fire'); ?></div>
                <div class="text-2xl font-bold text-white">
                    <span x-text="serverAge"></span> <span class="text-sm font-normal text-gray-400"><?php _e('days', 'wos-frost-fire'); ?></span>
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-400"><?php _e('Current Gen', 'wos-frost-fire'); ?></div>
                <div class="text-2xl font-bold text-fire-crystal" x-text="currentGen"></div>
            </div>
        </div>
    </div>
</div>
