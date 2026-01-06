document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const navbar = document.querySelector('.navbar');
    const hamburger = document.querySelector('.hamburger');
    const mobileMenuClose = document.querySelector('.mobile-menu-close');
    const navLinks = document.querySelector('.nav-links');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    const cartDropdown = document.querySelector('.cart-dropdown');

    // Create mobile overlay
    const mobileOverlay = document.createElement('div');
    mobileOverlay.classList.add('mobile-overlay');
    document.body.appendChild(mobileOverlay);

    // Touch handling variables
    let touchStartY = 0;
    let touchEndY = 0;
    let touchStartX = 0;
    let touchEndX = 0;

    // Functions
    const closeMenu = () => {
        navLinks.classList.remove('active');
        hamburger.classList.remove('active');
        mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
        closeAllDropdowns();
    };

    const closeAllDropdowns = () => {
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            dropdown.classList.remove('show');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) {
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                menu.style.transform = 'translateY(-10px)';
            }
        });
    };

    const toggleDropdown = (dropdown) => {
        const isOpen = dropdown.classList.contains('show');
        closeAllDropdowns();
        
        if (!isOpen) {
            dropdown.classList.add('show');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) {
                menu.style.opacity = '1';
                menu.style.visibility = 'visible';
                menu.style.transform = 'translateY(0)';
            }
        }
    };

    const updateCartDropdownPosition = () => {
        if (cartDropdown) {
            if (window.innerWidth <= 991) {
                cartDropdown.style.width = '100%';
                cartDropdown.style.maxHeight = '80vh';
                cartDropdown.style.overflowY = 'auto';
            } else {
                cartDropdown.style.width = '';
                cartDropdown.style.maxHeight = '';
                cartDropdown.style.overflowY = '';
            }
        }
    };

    // Event Listeners
    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        hamburger.classList.toggle('active');
        mobileOverlay.classList.toggle('active');
        document.body.style.overflow = navLinks.classList.contains('active') ? 'hidden' : '';
    });

    mobileMenuClose.addEventListener('click', closeMenu);

    document.addEventListener('click', (e) => {
        const dropdown = e.target.closest('.dropdown');
        const isDropdownToggle = e.target.closest('.dropdown-toggle');

        if (isDropdownToggle) {
            e.preventDefault();
            toggleDropdown(dropdown);
        } else if (!dropdown) {
            closeAllDropdowns();
        }

        if (!navLinks.contains(e.target) && !hamburger.contains(e.target)) {
            closeMenu();
        }
    });

    // Touch Events
    const handleTouchStart = (e) => {
        touchStartY = e.touches[0].clientY;
        touchStartX = e.touches[0].clientX;
    };

    const handleTouchEnd = (e) => {
        touchEndY = e.changedTouches[0].clientY;
        touchEndX = e.changedTouches[0].clientX;
        handleSwipe();
    };

    const handleSwipe = () => {
        const swipeDistanceY = touchEndY - touchStartY;
        const swipeDistanceX = touchEndX - touchStartX;
        
        if (Math.abs(swipeDistanceY) > 100 || (Math.abs(swipeDistanceX) > 100 && swipeDistanceX < 0)) {
            closeMenu();
        }
    };

    navLinks.addEventListener('touchstart', handleTouchStart);
    navLinks.addEventListener('touchend', handleTouchEnd);

    // Scroll handling
    let lastScrollTop = 0;
    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop;
    });

    // Resize handling
    window.addEventListener('resize', updateCartDropdownPosition);
    updateCartDropdownPosition();

    // Initialize active states
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});