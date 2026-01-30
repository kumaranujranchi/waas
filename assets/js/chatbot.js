/**
 * SiteOnSub Chatbot - Conversational Lead Generation
 */

document.addEventListener('DOMContentLoaded', function() {
    // State Management
    const STATE_KEY = 'sos_chat_state';
    let chatState = JSON.parse(localStorage.getItem(STATE_KEY)) || {
        isLeadCaptured: false,
        leadId: null,
        userName: 'User',
        isMaximized: false
    };

    // Create Chat Widget HTML
    const chatWidget = document.createElement('div');
    chatWidget.innerHTML = `
        <div id="sos-chatbot" class="fixed bottom-6 right-6 z-[9990] font-sans transition-all duration-300">
            <!-- Chat Window -->
            <div id="sos-chat-window" class="hidden flex flex-col bg-white dark:bg-[#1a1c2e] rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-white/10 transition-all duration-300 origin-bottom-right transform scale-95 opacity-0">
                <!-- Header -->
                <div class="bg-primary p-4 flex justify-between items-center text-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">smart_toy</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg leading-none">SOS Support</h3>
                            <span class="text-xs text-white/80">Support & Sales</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button id="sos-resizer-btn" class="hover:bg-white/20 p-1 rounded-full transition-colors flex" title="Maximize">
                            <span class="material-symbols-outlined text-[20px]">open_in_full</span>
                        </button>
                        <button id="sos-minimize-btn" class="hover:bg-white/20 p-1 rounded-full transition-colors" title="Minimize">
                            <span class="material-symbols-outlined text-[20px]">remove</span>
                        </button>
                        <button id="sos-close-btn" class="hover:bg-white/20 p-1 rounded-full transition-colors" title="Close">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                </div>

                <!-- CHAT INTERFACE -->
                <div id="sos-chat-interface" class="flex flex-col flex-1 h-full min-h-0">
                    <div id="sos-messages" data-lenis-prevent class="flex-1 min-h-0 p-4 overflow-y-auto space-y-4 bg-gray-50 dark:bg-[#0f0e1b] scroll-smooth overscroll-contain">
                        <!-- Initial AI Message will be appended here -->
                    </div>

                    <!-- Input Area -->
                    <div class="p-4 bg-white dark:bg-[#1a1c2e] border-t border-gray-100 dark:border-white/10 shrink-0">
                        <form id="sos-chat-form" class="flex items-center gap-2">
                            <input type="text" id="sos-chat-input" placeholder="Type your message..." 
                                class="flex-1 px-4 py-2 rounded-full border border-gray-200 dark:border-white/10 dark:bg-white/5 focus:outline-none focus:border-primary text-sm">
                            <button type="submit" class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-symbols-outlined text-sm">send</span>
                            </button>
                        </form>
                    </div>
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
    const minimizeBtn = document.getElementById('sos-minimize-btn');
    const resizerBtn = document.getElementById('sos-resizer-btn');
    const chatWindow = document.getElementById('sos-chat-window');
    
    // Screens
    const chatInterface = document.getElementById('sos-chat-interface');
    const chatForm = document.getElementById('sos-chat-form');
    const chatInput = document.getElementById('sos-chat-input');
    const messagesContainer = document.getElementById('sos-messages');

    // Resizing Logic
    const applySize = () => {
        if (chatState.isMaximized) {
            chatWindow.classList.remove('w-[350px]', 'h-[500px]');
            chatWindow.classList.add('w-[90vw]', 'md:w-[800px]', 'h-[80vh]');
            resizerBtn.querySelector('span').textContent = 'close_fullscreen';
            resizerBtn.title = 'Restore';
        } else {
            chatWindow.classList.remove('w-[90vw]', 'md:w-[800px]', 'h-[80vh]');
            chatWindow.classList.add('w-[350px]', 'h-[500px]');
            resizerBtn.querySelector('span').textContent = 'open_in_full';
            resizerBtn.title = 'Maximize';
        }
    };

    resizerBtn.addEventListener('click', () => {
        chatState.isMaximized = !chatState.isMaximized;
        localStorage.setItem(STATE_KEY, JSON.stringify(chatState));
        applySize();
    });

    // Initial size application
    applySize();

    // Initial Greeting
    const showGreeting = () => {
        if (messagesContainer.children.length === 0) {
            const greeting = chatState.isLeadCaptured 
                ? `Hi ${chatState.userName}! 👋 SiteOnSub me aapka swagat hai. Main aapki kaise help kar sakta hu?`
                : `Hello! 👋 SiteOnSub me aapka swagat hai. Main SiteOnSub ka AI Support agent hu.\n\nMain aapke answers de sakta hu, par usse pehle kya main aapka **naam** jaan sakta hu?`;
            appendMessage(greeting, 'ai');
        }
    };

    // Toggle Chat
    toggleBtn.addEventListener('click', () => {
        chatWindow.classList.remove('hidden');
        setTimeout(() => {
            chatWindow.classList.remove('scale-95', 'opacity-0');
            chatWindow.classList.add('scale-100', 'opacity-100');
        }, 10);
        toggleBtn.classList.add('hidden');
        showGreeting();
        chatInput.focus();
    });

    const closeAction = () => {
        chatWindow.classList.remove('scale-100', 'opacity-100');
        chatWindow.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            chatWindow.classList.add('hidden');
            toggleBtn.classList.remove('hidden');
        }, 300);
    };

    closeBtn.addEventListener('click', closeAction);
    minimizeBtn.addEventListener('click', closeAction);

    // Send Chat Message
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
                body: JSON.stringify({ 
                    message: message,
                    user_name: chatState.userName,
                    is_lead_captured: chatState.isLeadCaptured
                })
            });

            const data = await response.json();
            
            // Remove Loading
            const loadingEl = document.getElementById(loadingId);
            if (loadingEl) loadingEl.remove();

            if (data.reply) {
                appendMessage(data.reply, 'ai');
                
                // Handle Automatic Lead Capture from AI
                if (data.lead_captured && data.user_data) {
                    chatState.isLeadCaptured = true;
                    chatState.userName = data.user_data.name;
                    chatState.leadId = data.user_data.id;
                    localStorage.setItem(STATE_KEY, JSON.stringify(chatState));
                    console.log('Lead captured via conversation:', chatState.userName);
                }
            } else {
                appendMessage(data.error || 'Something went wrong.', 'ai', true);
            }

        } catch (error) {
            const loadingEl = document.getElementById(loadingId);
            if (loadingEl) loadingEl.remove();
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
        const path = window.location.pathname;
        if (path.includes('/admin/products/') || path.includes('/admin/users/') || path.includes('/dashboard/settings/')) {
            return '../../';
        }
        if (path.includes('/admin/') || path.includes('/dashboard/') || path.includes('/auth/')) {
            return '../';
        }
        return '';
    }
});
