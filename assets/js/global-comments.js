/**
 * Sistema Globale Commenti - JavaScript
 * Gestisce i commenti multilingue in tempo reale
 */

class GlobalComments {
    constructor(slug, language = 'it') {
        this.slug = slug;
        this.language = language;
        this.apiUrl = '/global_comments_api.php';
        this.commentsContainer = document.getElementById('global-comments-list');
        this.commentsCount = document.getElementById('global-comments-count');
        this.commentsForm = document.getElementById('global-comments-form');
        this.init();
    }
    
    init() {
        this.loadComments();
        this.setupForm();
        this.setupStarRating();
    }
    
    async loadComments() {
        try {
            const response = await fetch(`${this.apiUrl}?action=get&slug=${this.slug}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderComments(data.comments);
                this.updateCommentsCount(data.total_comments);
                this.updateRatingInfo(data.average_rating, data.total_ratings);
            } else {
                console.error('Error loading comments:', data.error);
            }
        } catch (error) {
            console.error('Error loading comments:', error);
        }
    }
    
    renderComments(comments) {
        if (!this.commentsContainer) return;
        
        if (comments.length === 0) {
            this.commentsContainer.innerHTML = `
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <i data-lucide="message-circle" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-gray-500 text-lg">Nessun commento ancora. Sii il primo a commentare!</p>
                </div>
            `;
            return;
        }
        
        this.commentsContainer.innerHTML = comments.map(comment => `
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 comment-item" data-comment-id="${comment.id}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-900">${this.escapeHtml(comment.author_name)}</h5>
                            <p class="text-sm text-gray-500">${this.formatDate(comment.created_at)}</p>
                        </div>
                    </div>
                    
                    ${comment.rating > 0 ? `
                        <div class="flex items-center space-x-1">
                            ${Array.from({length: 5}, (_, i) => `
                                <i data-lucide="star" class="w-4 h-4 ${i < comment.rating ? 'text-yellow-400 fill-current' : 'text-gray-300'}"></i>
                            `).join('')}
                        </div>
                    ` : ''}
                </div>
                
                <div class="text-gray-700 leading-relaxed">
                    ${this.escapeHtml(comment.content).replace(/\n/g, '<br>')}
                </div>
                
                ${comment.article_title ? `
                    <div class="mt-3 text-xs text-gray-400">
                        <i data-lucide="tag" class="w-3 h-3 inline mr-1"></i>
                        Commento su: ${this.escapeHtml(comment.article_title)}
                    </div>
                ` : ''}
            </div>
        `).join('');
        
        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
    
    updateCommentsCount(count) {
        if (this.commentsCount) {
            this.commentsCount.textContent = count;
        }
        
        // Update page title if exists
        const titleElement = document.querySelector('.comments-section-title');
        if (titleElement) {
            titleElement.innerHTML = `
                <i data-lucide="message-circle" class="w-8 h-8 mr-3 text-blue-600"></i>
                Commenti <span class="text-lg text-gray-500 ml-2">(${count})</span>
            `;
        }
    }
    
    updateRatingInfo(averageRating, totalRatings) {
        const ratingElement = document.getElementById('global-rating-info');
        if (ratingElement && totalRatings > 0) {
            ratingElement.innerHTML = `
                <div class="flex items-center space-x-2 mb-4 p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        ${Array.from({length: 5}, (_, i) => `
                            <i data-lucide="star" class="w-4 h-4 ${i < Math.round(averageRating) ? 'text-yellow-400 fill-current' : 'text-gray-300'}"></i>
                        `).join('')}
                    </div>
                    <span class="text-sm text-gray-600">
                        ${averageRating}/5 (${totalRatings} valutazioni)
                    </span>
                </div>
            `;
        }
    }
    
    setupForm() {
        if (!this.commentsForm) return;
        
        this.commentsForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(this.commentsForm);
            formData.append('action', 'add');
            formData.append('slug', this.slug);
            formData.append('language', this.language);
            
            const submitBtn = this.commentsForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            try {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Invio in corso...';
                
                const response = await fetch(this.apiUrl, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showMessage('Commento aggiunto con successo!', 'success');
                    this.commentsForm.reset();
                    this.resetStarRating();
                    // Ricarica i commenti dopo 1 secondo
                    setTimeout(() => this.loadComments(), 1000);
                } else {
                    this.showMessage(data.error || 'Errore durante l\'invio del commento', 'error');
                }
            } catch (error) {
                this.showMessage('Errore di connessione', 'error');
                console.error('Error submitting comment:', error);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }
    
    setupStarRating() {
        const starRatings = document.querySelectorAll('.star-rating');
        
        starRatings.forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                const ratingInput = document.querySelector('input[name="rating"]');
                if (ratingInput) {
                    ratingInput.value = rating;
                }
                
                this.updateVisualStars(rating);
            });
            
            star.addEventListener('mouseenter', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                this.updateVisualStars(rating, 'hover');
            });
            
            star.addEventListener('mouseleave', () => {
                const currentRating = document.querySelector('input[name="rating"]')?.value || 0;
                this.updateVisualStars(currentRating);
            });
        });
    }
    
    updateVisualStars(rating, mode = 'selected') {
        const starRatings = document.querySelectorAll('.star-rating');
        starRatings.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add(mode === 'hover' ? 'text-yellow-300' : 'text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400', 'text-yellow-300');
                star.classList.add('text-gray-300');
            }
        });
    }
    
    resetStarRating() {
        const ratingInput = document.querySelector('input[name="rating"]');
        if (ratingInput) {
            ratingInput.value = '';
        }
        this.updateVisualStars(0);
    }
    
    showMessage(message, type = 'info') {
        const existingMessage = document.getElementById('global-comment-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.id = 'global-comment-message';
        messageDiv.className = `p-4 rounded-lg mb-4 ${
            type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
            type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
            'bg-blue-100 border border-blue-400 text-blue-700'
        }`;
        messageDiv.textContent = message;
        
        if (this.commentsForm) {
            this.commentsForm.parentNode.insertBefore(messageDiv, this.commentsForm);
            
            // Remove message after 5 seconds
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 5000);
        }
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('it-IT', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
}

// Initialize global comments when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const commentsContainer = document.getElementById('global-comments-list');
    if (commentsContainer) {
        const slug = commentsContainer.getAttribute('data-slug');
        const language = document.documentElement.lang || 'it';
        
        if (slug) {
            window.globalComments = new GlobalComments(slug, language);
        }
    }
});