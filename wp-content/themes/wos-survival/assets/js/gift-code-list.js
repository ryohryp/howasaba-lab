document.addEventListener('DOMContentLoaded', function() {
    const copyButtons = document.querySelectorAll('.wos-copy-btn');

    copyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const code = this.getAttribute('data-code');
            const originalText = this.textContent;

            if (!code) return;

            // Copy to clipboard
            navigator.clipboard.writeText(code).then(() => {
                // Visual feedback
                this.textContent = 'Copied!';
                this.classList.add('copied');

                // Revert after 2 seconds
                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
                this.textContent = 'Error';
            });
        });
    });
});
