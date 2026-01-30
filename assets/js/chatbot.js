/**
 * SiteOnSub Chatbot
 */

document.addEventListener('DOMContentLoaded', function() {
    // State Management
    const STATE_KEY = 'sos_chat_state';
    let chatState = JSON.parse(localStorage.getItem(STATE_KEY)) || {
        isLeadCaptured: false,
        leadId: null,
        userName: 'User'
    };

    // Create Chat Widget HTML
    const chatWidget = document.createElement('div');
    chatWidget.innerHTML = `
        <div id="sos-chatbot" class="fixed bottom-6 right-6 z-[9990] font-sans">
            <!-- Chat Window -->
            <div id="sos-chat-window" class="hidden flex flex-col w-[350px] h-[500px] max-h-[80vh] bg-white dark:bg-[#1a1c2e] rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-white/10 transition-all duration-300 origin-bottom-right transform scale-95 opacity-0">
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
                        <button id="sos-minimize-btn" class="hover:bg-white/20 p-1 rounded-full transition-colors" title="Minimize">
                            <span class="material-symbols-outlined">remove</span>
                        </button>
                        <button id="sos-close-btn" class="hover:bg-white/20 p-1 rounded-full transition-colors" title="Close">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>

                <!-- LEAD FORM (Visible if no lead captured) -->
                <div id="sos-lead-form-container" class="${chatState.isLeadCaptured ? 'hidden' : 'flex'} flex-col flex-1 p-6 overflow-y-auto bg-gray-50 dark:bg-[#0f0e1b]">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-primary text-3xl">diversity_3</span>
                        </div>
                        <h4 class="font-bold text-gray-800 dark:text-white mb-1">Welcome! 👋</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Please introduce yourself to start chatting.</p>
                    </div>
                    
                    <form id="sos-lead-form" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 ml-1">Full Name</label>
                            <input type="text" name="name" required placeholder="John Doe"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 dark:bg-white/5 focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 ml-1">Phone Number</label>
                            <input type="tel" name="phone" required placeholder="9876543210" maxlength="10" pattern="[6-9][0-9]{9}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 dark:bg-white/5 focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none text-sm transition-all">
                            <p class="text-[10px] text-red-500 mt-1 hidden" id="phone-error">Please enter a valid 10-digit mobile number.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 ml-1">Email Address</label>
                            <input type="email" name="email" required placeholder="john@example.com"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 dark:bg-white/5 focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none text-sm transition-all">
                        </div>
                        <button type="submit" id="start-chat-btn" 
                            class="w-full bg-primary text-white py-3 rounded-xl font-medium shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                            <span>Start Chatting</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </form>
                </div>
                
                <!-- CHAT INTERFACE (Visible if lead captured) -->
                <!-- Added data-lenis-prevent to stop Lenis smooth scroll interference -->
                <div id="sos-chat-interface" class="${chatState.isLeadCaptured ? 'flex' : 'hidden'} flex-col flex-1 h-full min-h-0">
                    <div id="sos-messages" data-lenis-prevent class="flex-1 min-h-0 p-4 overflow-y-auto space-y-4 bg-gray-50 dark:bg-[#0f0e1b] scroll-smooth overscroll-contain">
                        <!-- Welcome Message -->
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary text-sm">smart_toy</span>
                            </div>
                            <div class="bg-white dark:bg-white/10 p-3 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 dark:text-gray-200 border border-gray-100 dark:border-white/5">
                                Hi ${chatState.userName}! 👋 Main SiteOnSub ka AI banking assistant hu. <br><br>
                                Aap mujhse website subscription plans, ownership, ya SEO ke baare me pooch sakte hain. Kaise help kar sakta hu?
                            </div>
                        </div>
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
    const chatWindow = document.getElementById('sos-chat-window');
    
    // Screens
    const leadFormContainer = document.getElementById('sos-lead-form-container');
    const leadForm = document.getElementById('sos-lead-form');
    const chatInterface = document.getElementById('sos-chat-interface');
    
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
        
        if (chatState.isLeadCaptured) {
            chatInput.focus();
        }
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

    // Handle Lead Form Submission
    leadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = document.getElementById('start-chat-btn');
        const formData = new FormData(leadForm);
        const name = formData.get('name').trim();
        const email = formData.get('email').trim();
        const phone = formData.get('phone').trim();

        // Strict Phone Validation
        const phoneRegex = /^[6-9]\d{9}$/;
        const phoneError = document.getElementById('phone-error');
        
        if (!phoneRegex.test(phone)) {
            phoneError.classList.remove('hidden');
            return;
        } else {
            phoneError.classList.add('hidden');
        }

        // Loading State
        const originalBtnContent = btn.innerHTML;
        btn.innerHTML = '<span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span> Connecting...';
        btn.disabled = true;

        try {
            const response = await fetch(extractBaseUrl() + 'api/chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    action: 'save_lead',
                    name: name,
                    email: email,
                    phone: phone
                })
            });

            const data = await response.json();

            if (data.success) {
                // Success! Switch to chat
                chatState = {
                    isLeadCaptured: true,
                    leadId: data.lead_id,
                    userName: name
                };
                localStorage.setItem(STATE_KEY, JSON.stringify(chatState));
                
                // Show chat interface
                leadFormContainer.classList.add('hidden');
                chatInterface.classList.remove('hidden');
                chatInterface.classList.add('flex');
                
                // Update Welcome Message Name
                const welcomeMsg = messagesContainer.querySelector('.text-sm');
                if (welcomeMsg) {
                    welcomeMsg.innerHTML = welcomeMsg.innerHTML.replace('Hi User!', `Hi ${name}!`);
                }

            } else {
                alert('Connection failed. Please try again.');
            }
        } catch (error) {
            console.error(error);
            alert('Something went wrong. Please check your connection.');
        } finally {
            btn.innerHTML = originalBtnContent;
            btn.disabled = false;
        }
    });

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
                    user_name: chatState.userName
                })
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
