/**
 * SiteOnSub Chatbot
 */

document.addEventListener('DOMContentLoaded', function() {
    // Create Chat Widget HTML
    const chatWidget = document.createElement('div');
    chatWidget.innerHTML = `
        <div id="sos-chatbot" class="fixed bottom-6 right-6 z-50 font-sans">
            <!-- Chat Window -->
            <div id="sos-chat-window" class="hidden flex-col w-[350px] h-[500px] bg-white dark:bg-[#1a1c2e] rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-white/10 transition-all duration-300 origin-bottom-right transform scale-95 opacity-0">
                <!-- Header -->
                <div class="bg-primary p-4 flex justify-between items-center text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">smart_toy</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg leading-none">SiteOnSub AI</h3>
                            <span class="text-xs text-white/80">Support & Sales</span>
                        </div>
                    </div>
                    <button id="sos-close-btn" class="hover:bg-white/20 p-1 rounded-full transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <!-- Messages Area -->
                <div id="sos-messages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50 dark:bg-[#0f0e1b] scroll-smooth" style="overscroll-behavior: contain;">
                    <!-- Welcome Message -->
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary text-sm">smart_toy</span>
                        </div>
                        <div class="bg-white dark:bg-white/10 p-3 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 dark:text-gray-200 border border-gray-100 dark:border-white/5">
                            Hi! 👋 Main SiteOnSub ka AI banking assistant hu. <br><br>
                            Aap mujhse website subscription plans, ownership, ya SEO ke baare me pooch sakte hain. Kaise help kar sakta hu?
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="p-4 bg-white dark:bg-[#1a1c2e] border-t border-gray-100 dark:border-white/10">
                    <form id="sos-chat-form" class="flex items-center gap-2">
                        <input type="text" id="sos-chat-input" placeholder="Type your message..." 
                            class="flex-1 px-4 py-2 rounded-full border border-gray-200 dark:border-white/10 dark:bg-white/5 focus:outline-none focus:border-primary text-sm">
                        <button type="submit" class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="material-symbols-outlined text-sm">send</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Toggle Button -->
            <button id="sos-toggle-btn" class="w-14 h-14 bg-primary text-white rounded-full shadow-lg hover:bg-primary/90 transition-all duration-300 flex items-center justify-center group relative">
                <span class="material-symbols-outlined text-3xl group-hover:scale-110 transition-transform">chat_bubble</span>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
        </div>
    `;
    document.body.appendChild(chatWidget);

    // Elements
    const toggleBtn = document.getElementById('sos-toggle-btn');
    const closeBtn = document.getElementById('sos-close-btn');
    const chatWindow = document.getElementById('sos-chat-window');
    const chatForm = document.getElementById('sos-chat-form');
    const chatInput = document.getElementById('sos-chat-input');
    const messagesContainer = document.getElementById('sos-messages');

    // Toggle Chat
    toggleBtn.addEventListener('click', () => {
        chatWindow.classList.remove('hidden');
        setTimeout(() => {
            chatWindow.classList.remove('scale-95', 'opacity-0');
            chatWindow.classList.add('scale-100', 'opacity-100');
        }, 10);
        toggleBtn.classList.add('hidden');
        chatInput.focus();
    });

    closeBtn.addEventListener('click', () => {
        chatWindow.classList.remove('scale-100', 'opacity-100');
        chatWindow.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            chatWindow.classList.add('hidden');
            toggleBtn.classList.remove('hidden');
        }, 300);
    });

    // Send Message
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        // Add User Message
        appendMessage(message, 'user');
        chatInput.value = '';
        chatInput.disabled = true;

        // Show Typing Indicator
        const loadingId = appendLoading();
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        try {
            const response = await fetch(extractBaseUrl() + 'api/chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            // Remove Loading
            document.getElementById(loadingId).remove();

            if (data.reply) {
                appendMessage(data.reply, 'ai');
            } else {
                appendMessage(data.error || 'Something went wrong.', 'ai', true);
            }

        } catch (error) {
            document.getElementById(loadingId).remove();
            appendMessage('Network error. Please try again.', 'ai', true);
        }

        chatInput.disabled = false;
        chatInput.focus();
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });

    function appendMessage(text, sender, isError = false) {
        const div = document.createElement('div');
        div.className = `flex gap-3 ${sender === 'user' ? 'flex-row-reverse' : ''}`;
        
        let iconHtml = '';
        if (sender === 'ai') {
            iconHtml = `
                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-sm">smart_toy</span>
                </div>`;
        } else {
            iconHtml = `
                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-gray-500 text-sm">person</span>
                </div>`;
        }

        const msgClass = sender === 'user' 
            ? 'bg-primary text-white rounded-tr-none' 
            : `bg-white dark:bg-white/10 text-gray-700 dark:text-gray-200 rounded-tl-none border border-gray-100 dark:border-white/5 ${isError ? 'text-red-500' : ''}`;

        div.innerHTML = `
            ${iconHtml}
            <div class="p-3 rounded-2xl shadow-sm text-sm max-w-[80%] leading-relaxed ${msgClass}">
                ${formatText(text)}
            </div>
        `;
        messagesContainer.appendChild(div);
    }

    function appendLoading() {
        const id = 'loading-' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex gap-3';
        div.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-primary text-sm">smart_toy</span>
            </div>
            <div class="bg-white dark:bg-white/10 p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 dark:border-white/5">
                <div class="flex gap-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        `;
        messagesContainer.appendChild(div);
        return id;
    }

    function formatText(text) {
        // Simple helper to convert newlines to <br> and bold text
        return text
            .replace(/\n/g, '<br>')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    }

    function extractBaseUrl() {
        // If we are in admin or auth or dashboard, we need to go up levels
        // Easiest way is to check the depth of the path
        const path = window.location.pathname;
        // Count how many segments are deeper than root. 
        // We assume site is at root or subdirectory. 
        // Better approach: Use the logo link or similar anchor if available, or just relative.. 
        // Let's stick to relative for simplicity in this PHP setup.
        
        if (path.includes('/admin/products/') || path.includes('/admin/users/') || path.includes('/dashboard/settings/')) {
            return '../../';
        }
        if (path.includes('/admin/') || path.includes('/dashboard/') || path.includes('/auth/')) {
            return '../';
        }
        return '';
    }
});
