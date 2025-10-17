import './bootstrap';

// ============================================
// PRELOADER
// ============================================
window.addEventListener('load', function() {
    const preloader = document.querySelector('.preloader');
    
    if (preloader) {
        // Fade out preloader
        preloader.style.opacity = '0';
        
        // Remove from DOM after fade out
        setTimeout(function() {
            preloader.style.display = 'none';
        }, 200);
    }
});

// ============================================
// MOBILE MENU TOGGLE
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        // Toggle menu
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('hidden');
            
            // Change icon
            const icon = this.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                mobileMenu.classList.add('hidden');
                const icon = mobileMenuBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
});

// ============================================
// AUTO DISMISS ALERTS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-notification');
    
    alerts.forEach(function(alert) {
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// ============================================
// AUDIO PLAYER ENHANCEMENTS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const audioPlayers = document.querySelectorAll('audio');
    
    audioPlayers.forEach(function(audio) {
        // Pause other audio players when one starts playing
        audio.addEventListener('play', function() {
            audioPlayers.forEach(function(otherAudio) {
                if (otherAudio !== audio) {
                    otherAudio.pause();
                }
            });
        });
        
        // Add error handling
        audio.addEventListener('error', function() {
            console.error('Error loading audio file');
            const parent = audio.parentElement;
            if (parent) {
                const errorMsg = document.createElement('p');
                errorMsg.className = 'text-red-400 text-sm mt-2';
                errorMsg.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Không thể tải file nhạc';
                audio.style.display = 'none';
                parent.appendChild(errorMsg);
            }
        });
    });
});

// ============================================
// SMOOTH SCROLL
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '#!') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
});

// ============================================
// SEARCH INPUT FOCUS ENHANCEMENT
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="keyword"]');
    
    if (searchInput) {
        // Auto focus on page load (optional)
        // searchInput.focus();
        
        // Clear button functionality
        const clearBtn = document.querySelector('.search-clear-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.focus();
            });
        }
        
        // Show/hide clear button based on input
        searchInput.addEventListener('input', function() {
            if (clearBtn) {
                clearBtn.style.display = this.value ? 'block' : 'none';
            }
        });
    }
});

// ============================================
// LAZY LOADING IMAGES
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(function(img) {
        imageObserver.observe(img);
    });
});

// ============================================
// BACK TO TOP BUTTON
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Create back to top button
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.className = 'fixed bottom-8 right-8 bg-purple-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-purple-700 transition-all duration-300 z-50 hidden items-center justify-center';
    backToTopBtn.setAttribute('aria-label', 'Back to top');
    document.body.appendChild(backToTopBtn);
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.remove('hidden');
            backToTopBtn.classList.add('flex');
        } else {
            backToTopBtn.classList.add('hidden');
            backToTopBtn.classList.remove('flex');
        }
    });
    
    // Scroll to top when clicked
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});

// ============================================
// FAVORITE BUTTON FUNCTIONALITY
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    
    favoriteButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('far')) {
                // Add to favorites
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#ef4444'; // red-500
                
                // Show notification
                showNotification('Đã thêm vào yêu thích', 'success');
            } else {
                // Remove from favorites
                icon.classList.remove('fas');
                icon.classList.add('far');
                icon.style.color = '';
                
                // Show notification
                showNotification('Đã xóa khỏi yêu thích', 'info');
            }
        });
    });
});

// ============================================
// NOTIFICATION SYSTEM
// ============================================
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    notification.className = `fixed top-20 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 alert-notification`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(function() {
        notification.style.opacity = '0';
        setTimeout(function() {
            notification.remove();
        }, 300);
    }, 3000);
}

// ============================================
// KEYBOARD SHORTCUTS
// ============================================
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K: Focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('input[name="keyword"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
    
    // Escape: Close mobile menu
    if (e.key === 'Escape') {
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            if (mobileMenuBtn) {
                const icon = mobileMenuBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    }
});

// ============================================
// FORM VALIDATION ENHANCEMENT
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    
                    // Remove error styling on input
                    field.addEventListener('input', function() {
                        this.classList.remove('border-red-500');
                    }, { once: true });
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Vui lòng điền đầy đủ thông tin', 'error');
            }
        });
    });
});

// ============================================
// PERFORMANCE: Debounce Function
// ============================================
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ============================================
// EXPORT FUNCTIONS FOR GLOBAL USE
// ============================================
window.showNotification = showNotification;
window.debounce = debounce;

console.log('🎵 MusicWeb initialized successfully!');