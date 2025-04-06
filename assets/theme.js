import './passwordForm'
import './styles/Test/_passwordModal.scss'
document.addEventListener('DOMContentLoaded', function () {
    const practiseLink = document.querySelector('.practise-link');
    const tooltip = document.getElementById('tooltip');
    const testLink = document.querySelector('.test-link');

    practiseLink.addEventListener('click', function (event) {
        const isUserLoggedIn = this.getAttribute('data-user') === 'true';

        if (!isUserLoggedIn) {
            event.preventDefault();

            const tooltipText = this.getAttribute('data-tooltip');
            tooltip.textContent = tooltipText;

            const rect = this.getBoundingClientRect();
            tooltip.style.top = rect.bottom + window.scrollY + 'px';
            tooltip.style.left = rect.left + window.scrollX + 'px';
            tooltip.style.display = 'block';

            setTimeout(() => {
                tooltip.style.display = 'none';
            }, 3000);
        }
    });

    testLink.addEventListener('click', function (event) {
        const exampleCount = parseInt(this.getAttribute('data-examples'), 10);

        if (exampleCount < 5) {
            event.preventDefault();

            const tooltipText = this.getAttribute('data-tooltip');
            tooltip.textContent = tooltipText;

            const rect = this.getBoundingClientRect();
            tooltip.style.top = rect.bottom + window.scrollY + 'px';
            tooltip.style.left = rect.left + window.scrollX + 'px';
            tooltip.style.display = 'block';

            setTimeout(() => {
                tooltip.style.display = 'none';
            }, 3000);
        }
    });
});
