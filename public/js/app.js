document.addEventListener('DOMContentLoaded', function() {
    // Back to top functionality
    const backToTop = document.querySelector('.back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            backToTop.classList.toggle('show', window.scrollY > 300);
        });

        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', function() {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltipEl = document.createElement('div');
            tooltipEl.className = 'tooltip';
            tooltipEl.textContent = tooltipText;
            document.body.appendChild(tooltipEl);

            const rect = this.getBoundingClientRect();
            const tooltipRect = tooltipEl.getBoundingClientRect();
            tooltipEl.style.top = rect.top - tooltipRect.height - 10 + 'px';
            tooltipEl.style.left = rect.left + (rect.width - tooltipRect.width) / 2 + 'px';
            tooltipEl.style.opacity = '1';
        });

        tooltip.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });

    // Form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    let errorMessage = field.getAttribute('data-error') || 'This field is required';
                    let errorEl = field.nextElementSibling;
                    
                    if (!errorEl || !errorEl.classList.contains('error-message')) {
                        errorEl = document.createElement('div');
                        errorEl.className = 'error-message';
                        field.parentNode.insertBefore(errorEl, field.nextSibling);
                    }
                    
                    errorEl.textContent = errorMessage;
                } else {
                    field.classList.remove('error');
                    const errorEl = field.nextElementSibling;
                    if (errorEl && errorEl.classList.contains('error-message')) {
                        errorEl.remove();
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Clear error on input
        form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('error');
                const errorEl = this.nextElementSibling;
                if (errorEl && errorEl.classList.contains('error-message')) {
                    errorEl.remove();
                }
            });
        });
    });

    // Lazy loading images
    const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
});