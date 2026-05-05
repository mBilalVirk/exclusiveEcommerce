{{-- resources/views/components/chatbot.blade.php --}}
{{-- Include this in your main layout: @include('components.chatbot') --}}

<div id="chatbot-wrapper">

    {{-- Floating Toggle Button --}}
    <button id="chatbot-toggle" onclick="toggleChatbot()" title="Chat with AI Assistant">
        <span id="chatbot-icon-open">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
        </span>
        <span id="chatbot-icon-close" style="display:none;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </span>
        <span id="chatbot-badge" style="display:none;">1</span>
    </button>

    {{-- Chat Window --}}
    <div id="chatbot-window">

        {{-- Header --}}
        <div class="chatbot-header">
            <div class="chatbot-header-left">
                <div class="chatbot-avatar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        <circle cx="12" cy="16" r="1" fill="currentColor"></circle>
                    </svg>
                </div>
                <div>
                    <div class="chatbot-name">AI Assistant</div>
                    <div class="chatbot-status">
                        <span class="status-dot"></span> Online
                    </div>
                </div>
            </div>
            <button class="chatbot-close-btn" onclick="toggleChatbot()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Quick FAQ Chips --}}
        <div class="faq-chips" id="faq-chips">
            <p class="faq-label">Quick questions:</p>
            <div class="chips-row">
                <button class="chip" onclick="sendQuick('What is today\'s date?')">📅 Today's date</button>
                <button class="chip" onclick="sendQuick('How do I create a post?')">✏️ Create post</button>
                <button class="chip" onclick="sendQuick('How do I add a friend?')">👥 Add friend</button>
                <button class="chip" onclick="sendQuick('How do I change my password?')">🔒 Password</button>
                <button class="chip" onclick="sendQuick('How do I make my profile private?')">🛡️ Privacy</button>
                <button class="chip" onclick="sendQuick('How do I report someone?')">🚩 Report user</button>
            </div>
        </div>

        {{-- Messages --}}
        <div class="chatbot-messages" id="chatbot-messages">
            <div class="message bot-message" id="welcome-msg">
                <div class="message-avatar bot-avatar">🤖</div>
                <div class="message-bubble">
                    <p>Hello! 👋 I'm your AI assistant. How can I help you today?</p>
                    <span class="message-time">{{ now()->format('h:i A') }}</span>
                </div>
            </div>
        </div>

        {{-- Typing Indicator --}}
        <div class="typing-indicator" id="typing-indicator" style="display:none;">
            <div class="message-avatar bot-avatar">🤖</div>
            <div class="typing-bubble">
                <span></span><span></span><span></span>
            </div>
        </div>

        {{-- Input --}}
        <div class="chatbot-input-area">
            <div class="input-row">
                <input type="text" id="chatbot-input" placeholder="Ask me anything..."
                    onkeydown="handleKey(event)" autocomplete="off" />
                <button id="send-btn" onclick="sendMessage()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </div>
            <p class="input-hint">Powered by AI • Press Enter to send</p>
        </div>

    </div>
</div>

{{-- Styles --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap');

    :root {
        --cb-primary: #2563eb;
        --cb-primary-dark: #1d4ed8;
        --cb-primary-light: #eff6ff;
        --cb-bg: #ffffff;
        --cb-surface: #f8fafc;
        --cb-border: #e2e8f0;
        --cb-text: #0f172a;
        --cb-text-muted: #64748b;
        --cb-bot-bg: #f1f5f9;
        --cb-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 4px 20px rgba(37, 99, 235, 0.1);
        --cb-radius: 20px;
    }

    #chatbot-wrapper {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99999;
        font-family: 'DM Sans', sans-serif;
    }

    /* Toggle Button */
    #chatbot-toggle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--cb-primary), #3b82f6);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.45);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        margin-left: auto;
    }

    #chatbot-toggle:hover {
        transform: scale(1.08);
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.55);
    }

    #chatbot-toggle:active {
        transform: scale(0.95);
    }

    #chatbot-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: white;
        font-size: 11px;
        font-weight: 600;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        animation: pulse-badge 2s infinite;
    }

    @keyframes pulse-badge {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }

    /* Chat Window */
    #chatbot-window {
        position: absolute;
        bottom: 72px;
        right: 0;
        width: 360px;
        background: var(--cb-bg);
        border-radius: var(--cb-radius);
        box-shadow: var(--cb-shadow);
        border: 1px solid var(--cb-border);
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        max-height: 560px;
    }

    #chatbot-window.open {
        display: flex;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(16px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Header */
    .chatbot-header {
        background: linear-gradient(135deg, var(--cb-primary), #3b82f6);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
    }

    .chatbot-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chatbot-avatar {
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .chatbot-name {
        font-weight: 600;
        font-size: 15px;
        letter-spacing: -0.2px;
    }

    .chatbot-status {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 1px;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        background: #4ade80;
        border-radius: 50%;
        display: inline-block;
        animation: blink 2s infinite;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }

    .chatbot-close-btn {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .chatbot-close-btn:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    /* FAQ Chips */
    .faq-chips {
        padding: 10px 12px 6px;
        background: var(--cb-surface);
        border-bottom: 1px solid var(--cb-border);
    }

    .faq-label {
        font-size: 11px;
        color: var(--cb-text-muted);
        font-weight: 500;
        margin: 0 0 6px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .chips-row {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .chip {
        background: white;
        border: 1px solid var(--cb-border);
        color: var(--cb-text);
        font-size: 11.5px;
        font-family: 'DM Sans', sans-serif;
        padding: 4px 10px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.15s ease;
        font-weight: 500;
        white-space: nowrap;
    }

    .chip:hover {
        background: var(--cb-primary-light);
        border-color: var(--cb-primary);
        color: var(--cb-primary);
        transform: translateY(-1px);
    }

    /* Messages */
    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 14px 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-height: 200px;
        max-height: 280px;
        scroll-behavior: smooth;
    }

    .chatbot-messages::-webkit-scrollbar {
        width: 4px;
    }

    .chatbot-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    .chatbot-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .message {
        display: flex;
        align-items: flex-end;
        gap: 7px;
        animation: fadeInMsg 0.2s ease;
    }

    @keyframes fadeInMsg {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.user-message {
        flex-direction: row-reverse;
    }

    .message-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .bot-avatar {
        background: var(--cb-primary-light);
    }

    .user-avatar-img {
        background: #e2e8f0;
        font-size: 12px;
        font-weight: 600;
        color: var(--cb-text-muted);
    }

    .message-bubble {
        max-width: 78%;
        padding: 9px 13px;
        border-radius: 16px;
        font-size: 13.5px;
        line-height: 1.5;
        position: relative;
    }

    .message-bubble p {
        margin: 0;
        color: var(--cb-text);
    }

    .bot-message .message-bubble {
        background: var(--cb-bot-bg);
        border-bottom-left-radius: 4px;
    }

    .user-message .message-bubble {
        background: linear-gradient(135deg, var(--cb-primary), #3b82f6);
        border-bottom-right-radius: 4px;
    }

    .user-message .message-bubble p {
        color: white;
    }

    .message-time {
        display: block;
        font-size: 10px;
        color: var(--cb-text-muted);
        margin-top: 4px;
        text-align: right;
    }

    .user-message .message-time {
        color: rgba(255, 255, 255, 0.7);
    }

    /* Typing Indicator */
    .typing-indicator {
        display: flex;
        align-items: flex-end;
        gap: 7px;
        padding: 0 12px 8px;
    }

    .typing-bubble {
        background: var(--cb-bot-bg);
        border-radius: 16px;
        border-bottom-left-radius: 4px;
        padding: 10px 14px;
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .typing-bubble span {
        width: 7px;
        height: 7px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typing-dot 1.2s infinite;
    }

    .typing-bubble span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-bubble span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing-dot {

        0%,
        60%,
        100% {
            transform: translateY(0);
            opacity: 0.5;
        }

        30% {
            transform: translateY(-6px);
            opacity: 1;
        }
    }

    /* Input Area */
    .chatbot-input-area {
        padding: 10px 12px 10px;
        border-top: 1px solid var(--cb-border);
        background: var(--cb-bg);
    }

    .input-row {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    #chatbot-input {
        flex: 1;
        border: 1.5px solid var(--cb-border);
        border-radius: 12px;
        padding: 9px 13px;
        font-size: 13.5px;
        font-family: 'DM Sans', sans-serif;
        outline: none;
        color: var(--cb-text);
        background: var(--cb-surface);
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    #chatbot-input:focus {
        border-color: var(--cb-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        background: white;
    }

    #chatbot-input::placeholder {
        color: #94a3b8;
    }

    #send-btn {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, var(--cb-primary), #3b82f6);
        border: none;
        border-radius: 10px;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.15s, opacity 0.15s;
        flex-shrink: 0;
    }

    #send-btn:hover {
        transform: scale(1.05);
    }

    #send-btn:active {
        transform: scale(0.95);
    }

    #send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .input-hint {
        font-size: 10.5px;
        color: #94a3b8;
        margin: 5px 0 0;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 420px) {
        #chatbot-window {
            width: calc(100vw - 32px);
            right: -8px;
        }

        #chatbot-wrapper {
            right: 16px;
            bottom: 16px;
        }
    }
</style>

{{-- Script --}}
<script>
    let chatOpen = false;
    let conversationHistory = [];
    let isLoading = false;
    let hasNewMessage = false;

    // Get auth token from Laravel session meta tag or localStorage
    function getToken() {
        // For React/SPA with localStorage:
        const userInfo = localStorage.getItem('user-info');
        if (userInfo) {
            return JSON.parse(userInfo).data?.token;
        }
        // For Blade with CSRF (fallback):
        return document.querySelector('meta[name="api-token"]')?.content || null;
    }

    function toggleChatbot() {
        chatOpen = !chatOpen;
        const window_el = document.getElementById('chatbot-window');
        const iconOpen = document.getElementById('chatbot-icon-open');
        const iconClose = document.getElementById('chatbot-icon-close');
        const badge = document.getElementById('chatbot-badge');

        if (chatOpen) {
            window_el.classList.add('open');
            iconOpen.style.display = 'none';
            iconClose.style.display = 'flex';
            badge.style.display = 'none';
            hasNewMessage = false;
            document.getElementById('chatbot-input').focus();
            scrollToBottom();
        } else {
            window_el.classList.remove('open');
            iconOpen.style.display = 'flex';
            iconClose.style.display = 'none';
        }
    }

    function handleKey(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    }

    function sendQuick(text) {
        document.getElementById('chatbot-input').value = text;
        // Hide chips after first use
        document.getElementById('faq-chips').style.display = 'none';
        sendMessage();
    }

    async function sendMessage() {
        const input = document.getElementById('chatbot-input');
        const text = input.value.trim();
        if (!text || isLoading) return;

        // Hide FAQ chips once user starts chatting
        document.getElementById('faq-chips').style.display = 'none';

        // Append user message to UI
        appendMessage('user', text);
        input.value = '';

        // Add to history
        conversationHistory.push({
            role: 'user',
            content: text
        });

        // Show typing
        setLoading(true);

        try {
            const token = getToken();
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            };
            if (token) headers['Authorization'] = 'Bearer ' + token;

            const response = await fetch('/api/chatbot', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({
                    messages: conversationHistory
                }),
            });

            const data = await response.json();

            if (response.ok && data.reply) {
                conversationHistory.push({
                    role: 'assistant',
                    content: data.reply
                });
                appendMessage('bot', data.reply);
            } else {
                appendMessage('bot', '⚠️ Sorry, I could not get a response. Please try again.');
            }
        } catch (error) {
            appendMessage('bot', '⚠️ Connection error. Please check your internet and try again.');
            console.error('Chatbot error:', error);
        } finally {
            setLoading(false);
        }
    }

    function appendMessage(sender, text) {
        const container = document.getElementById('chatbot-messages');
        const now = new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });

        const msgDiv = document.createElement('div');
        msgDiv.className = `message ${sender === 'user' ? 'user-message' : 'bot-message'}`;

        const avatarDiv = document.createElement('div');
        avatarDiv.className = `message-avatar ${sender === 'user' ? 'user-avatar-img' : 'bot-avatar'}`;
        avatarDiv.textContent = sender === 'user' ? '👤' : '🤖';

        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';

        const p = document.createElement('p');
        p.textContent = text;

        const timeSpan = document.createElement('span');
        timeSpan.className = 'message-time';
        timeSpan.textContent = now;

        bubbleDiv.appendChild(p);
        bubbleDiv.appendChild(timeSpan);
        msgDiv.appendChild(avatarDiv);
        msgDiv.appendChild(bubbleDiv);
        container.appendChild(msgDiv);

        scrollToBottom();

        // Show badge if chat is closed
        if (!chatOpen) {
            document.getElementById('chatbot-badge').style.display = 'flex';
        }
    }

    function setLoading(state) {
        isLoading = state;
        document.getElementById('typing-indicator').style.display = state ? 'flex' : 'none';
        document.getElementById('send-btn').disabled = state;
        if (state) scrollToBottom();
    }

    function scrollToBottom() {
        setTimeout(() => {
            const container = document.getElementById('chatbot-messages');
            container.scrollTop = container.scrollHeight;
        }, 50);
    }
</script>
