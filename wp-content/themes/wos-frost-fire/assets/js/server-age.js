document.addEventListener('alpine:init', () => {
    Alpine.data('serverAgeCalculator', () => ({
        inputMode: 'date', // 'date' or 'server'
        serverDate: '',
        serverNumber: '',
        serverAge: null,
        currentGen: null,

        init() {
            // Optional: Load saved state if needed
        },

        calculate() {
            let startDate;

            if (this.inputMode === 'server') {
                // Placeholder: Calculate date from server number
                // logic would go here. For now, we'll simulate it or ask user for date.
                // In a real scenario, we might need a lookup table or API.
                // For this demo, let's just alert or switch to date mode if invalid.
                if (!this.serverNumber) return;

                // Mock logic: assume server 1 started on 2023-01-01 and new server every day (just for demo)
                // This is obviously incorrect and needs real data.
                // Better approach for MVP: focus on Date input.
                alert('Server number lookup requires a database. Please use Date mode for accurate results.');
                return;
            } else {
                if (!this.serverDate) return;
                startDate = new Date(this.serverDate);
            }

            const today = new Date();
            const diffTime = Math.abs(today - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            this.serverAge = diffDays;
            this.currentGen = this.determineGeneration(diffDays);
        },

        determineGeneration(days) {
            // Approximate Gen unlock schedule (needs verification with game data)
            if (days < 60) return 'Gen 1';
            if (days < 120) return 'Gen 2';
            if (days < 180) return 'Gen 3';
            if (days < 240) return 'Gen 4';
            if (days < 300) return 'Gen 5';
            if (days < 360) return 'Gen 6';
            return 'Gen 7+'; // and so on
        }
    }))
})
