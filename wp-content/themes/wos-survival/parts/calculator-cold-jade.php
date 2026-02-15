<?php
/**
 * Cold Jade Coin Calculator Component
 * 
 * Usage: get_template_part('parts/calculator-cold-jade');
 */
?>
<div x-data="{
    items: [
        { id: 1, name: '永久都市スキン (舞う獅子)', cost: 150000, quantity: 0 },
        { id: 2, name: '無名の欠片 (1枚)', cost: 2000, quantity: 0 },
        { id: 3, name: '火晶 (1個)', cost: 500, quantity: 0 },
        { id: 4, name: '高級都市移転', cost: 3000, quantity: 0 }
    ],
    f2pCap: 50000,
    get totalCost() {
        return this.items.reduce((sum, item) => sum + (item.cost * (parseInt(item.quantity) || 0)), 0);
    }
}" class="w-full max-w-2xl mx-auto p-6 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg text-white">

    <h3 class="text-2xl font-bold mb-6 text-center border-b border-white/10 pb-4">
        <span class="text-[#ff3d00]">❄️</span> 寒玉コイン計算機 <span class="text-[#ff3d00]">❄️</span>
    </h3>

    <div class="space-y-4">
        <template x-for="item in items" :key="item.id">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-3 rounded-lg bg-black/20 hover:bg-black/30 transition-colors">
                <div class="flex-1">
                    <div class="font-bold text-lg" x-text="item.name"></div>
                    <div class="text-sm text-gray-300">単価: <span x-text="item.cost.toLocaleString()"></span> コイン</div>
                </div>
                <div class="flex items-center gap-3">
                    <label :for="'item-' + item.id" class="text-sm font-medium">個数:</label>
                    <input type="number" :id="'item-' + item.id" x-model.number="item.quantity" min="0" placeholder="0"
                           class="w-24 px-3 py-2 bg-slate-800/80 border border-slate-600 rounded text-white focus:outline-none focus:border-[#ff3d00] focus:ring-1 focus:ring-[#ff3d00] text-right font-mono">
                </div>
            </div>
        </template>
    </div>

    <div class="mt-8 pt-6 border-t border-white/10">
        <div class="flex flex-col items-end gap-2">
            <div class="text-sm text-gray-300">合計必要コイン数</div>
            <div class="text-3xl font-bold text-[#ff3d00]" x-text="totalCost.toLocaleString()"></div>
        </div>

        <div x-show="totalCost > f2pCap" x-transition.opacity
             class="mt-4 p-4 rounded bg-red-500/20 border border-red-500/50 text-red-200 text-center font-bold">
            ⚠️ 課金が必要です (無課金上限目安: <span x-text="f2pCap.toLocaleString()"></span>)
        </div>
    </div>

</div>
