<?php
session_start();
$ROOT = "/ai-roadtrip-planner/frontend";
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . "$ROOT/includes/header.php"; ?>

<!-- Styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />

<style>
    :root {
        --primary-color: #10a37f;
        --primary-dark: #0d8c6d;
        --text-color: #374151;
        --light-gray: #f3f4f6;
        --medium-gray: #e5e7eb;
        --dark-gray: #6b7280;
        --white: #ffffff;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        color: var(--text-color);
        background-color:rgb(244, 244, 244);
        background-image: radial-gradient(circle, rgb(189, 228, 191) 1px, transparent 1px);
        background-size: 20px 20px;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding-top: 70px;
        justify-content: center;
        align-items: center;
    }

    .chat-container {
        display: flex;
        flex-direction: column;
        height: 80vh;
        width: 100%;
        max-width: 1000px;
        margin-top: 100px;
        background-color: var(--white);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
        overflow: hidden;
        position: relative;
        transition: all 0.3s ease;
        padding: 0 1rem;
        box-sizing: border-box;
        border: 1px solid var(--medium-gray);
    }

    .chat-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--medium-gray);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: rgb(191, 223, 215);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem 0.5rem 0 0;
        position: sticky;
        top: 0;
        z-index: 10;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
    }

    .chat-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .chat-icon {
        color: var(--primary-color);
    }

    .chat-main {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .message {
        max-width: 800px;
        margin: 0 auto;
        width: 100%;
    }

    .user-message {
        background-color: var(--white);
        padding: 1rem 0;
    }

    .bot-message {
        background-color: var(--light-gray);
        padding: 1.5rem 0;
    }

    .message-content {
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .bot-message .message-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .message-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        align-items: center;
    }

    .action-btn {
        background: none;
        border: none;
        color: var(--dark-gray);
        cursor: pointer;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        transition: background-color 0.2s ease;
    }

    .action-btn:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .thumbs-up, .thumbs-down, .copy-btn {
        font-size: 1rem;
    }

    .regenerate-btn, .copy-btn, .clear-history-btn {
        color: var(--primary-color);
    }

    .copy-btn.copied {
        color: var(--primary-dark);
    }

    .clear-history-btn {
        margin-left: auto; /* Pushes it to the right */
    }

    .chat-input-container {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--medium-gray);
        background-color: var(--white);
    }

    .chat-input-wrapper {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
    }

    .chat-input {
        width: 100%;
        padding: 0.75rem 3rem 0.75rem 1rem;
        border: 1px solid var(--medium-gray);
        border-radius: 0.5rem;
        font-size: 1rem;
        outline: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .chat-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(16, 163, 127, 0.2);
    }

    .send-btn {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--primary-color);
        cursor: pointer;
        font-size: 1.25rem;
    }

    .typing-indicator {
        display: none;
        padding: 1rem 0;
        max-width: 800px;
        margin: 0 auto;
    }

    .typing-dots {
        display: flex;
        gap: 0.25rem;
    }

    .typing-dot {
        width: 0.5rem;
        height: 0.5rem;
        background-color: var(--dark-gray);
        border-radius: 50%;
        opacity: 0.4;
        animation: typingAnimation 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingAnimation {
        0%, 60%, 100% { opacity: 0.4; transform: translateY(0); }
        30% { opacity: 1; transform: translateY(-3px); }
    }

    ul.bot-response-list {
        list-style-type: none;
        padding-left: 0;
        margin: 0;
    }

    ul.bot-response-list li {
        padding-left: 1.5rem;
        position: relative;
        margin-bottom: 0.5rem;
    }

    ul.bot-response-list li:before {
        content: "•";
        position: absolute;
        left: 0;
        color: var(--primary-color);
        font-weight: bold;
    }

    .login-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        padding: 2rem;
        text-align: center;
    }

    .login-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-color);
    }

    .login-subtitle {
        font-size: 1.125rem;
        color: var(--dark-gray);
        margin-bottom: 2rem;
        max-width: 500px;
    }

    .login-btn, .signup-btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        margin: 0 0.5rem;
    }

    .login-btn {
        background-color: var(--primary-color);
        color: white;
    }

    .login-btn:hover {
        background-color: var(--primary-dark);
    }

    .signup-btn {
        background-color: var(--white);
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    .signup-btn:hover {
        background-color: rgba(16, 163, 127, 0.1);
    }
</style>

<body>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="chat-container">
            <header class="chat-header">
                <div class="chat-title">
                    <i class="fas fa-route chat-icon"></i>
                    <span>Road Trip Planner Chatbot</span>
                </div>
            </header>
            
            <main class="chat-main" id="chat-window">
                <!-- History will be loaded here -->
            </main>
            
            <div id="typing-indicator" class="typing-indicator">
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
            
            <div class="chat-input-container">
                <div class="chat-input-wrapper">
                    <input type="text" class="chat-input" id="user-input" placeholder="Message AI Road Trip Planner..." />
                    <button class="send-btn" id="send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="login-container">
            <h1 class="login-title">AI Road Trip Planner</h1>
            <p class="login-subtitle">Get personalized road trip plans with AI-powered recommendations for routes, stops, and attractions.</p>
            <div>
                <a href="<?= $ROOT ?>/login.php" class="login-btn">Log in</a>
                <a href="<?= $ROOT ?>/signup.php" class="signup-btn">Sign up</a>
            </div>
        </div>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sessionId = "user_<?php echo $_SESSION['user_id'] ?? 'guest'; ?>";
        const chatWindow = document.getElementById("chat-window");
        const userInput = document.getElementById("user-input");
        const sendBtn = document.getElementById("send-btn");
        const typingIndicator = document.getElementById("typing-indicator");

        if (!chatWindow || !userInput || !sendBtn || !typingIndicator) {
            console.error("Missing required elements!");
            return;
        }

        // Load chat history from localStorage
        function loadChatHistory() {
            const history = JSON.parse(localStorage.getItem(`chatHistory_${sessionId}`)) || [];
            if (history.length === 0) {
                // Show welcome message if no history
                appendMessage("bot", `
                    <p>Hello! I'm your Road Trip Assistant. I can help you:</p>
                    <ul class="bot-response-list">
                        <li>Plan scenic routes between destinations</li>
                        <li>Suggest interesting stops along your journey</li>
                        <li>Recommend accommodations and dining options</li>
                        <li>Provide weather and road condition updates</li>
                    </ul>
                    <p>Where would you like to go today?</p>
                `);
            } else {
                history.forEach(msg => {
                    appendMessage(msg.role, msg.content, msg.originalMessage || '');
                });
            }
        }

        // Save message to localStorage
        function saveMessage(role, content, originalMessage = '') {
            const history = JSON.parse(localStorage.getItem(`chatHistory_${sessionId}`)) || [];
            history.push({ role, content, originalMessage, timestamp: new Date().toISOString() });
            localStorage.setItem(`chatHistory_${sessionId}`, JSON.stringify(history));
        }

        // Clear chat history
        function clearChatHistory() {
            localStorage.removeItem(`chatHistory_${sessionId}`);
            chatWindow.innerHTML = '';
            appendMessage("bot", `
                <p>Hello! I'm your Road Trip Assistant. I can help you:</p>
                <ul class="bot-response-list">
                    <li>Plan scenic routes between destinations</li>
                    <li>Suggest interesting stops along your journey</li>
                    <li>Recommend accommodations and dining options</li>
                    <li>Provide weather and road condition updates</li>
                </ul>
                <p>Where would you like to go today?</p>
            `);
        }

        function appendMessage(role, content, originalMessage = '') {
            const messageDiv = document.createElement("div");
            messageDiv.className = `message ${role}-message`;

            const contentDiv = document.createElement("div");
            contentDiv.className = "message-content";

            if (typeof content === 'string') {
                contentDiv.innerHTML = content.trim();
            } else if (Array.isArray(content)) {
                const list = document.createElement("ul");
                list.className = "bot-response-list";
                content.forEach(item => {
                    const li = document.createElement("li");
                    li.textContent = item;
                    list.appendChild(li);
                });
                contentDiv.appendChild(list);
            }

            if (role === "bot") {
                const actionsDiv = document.createElement("div");
                actionsDiv.className = "message-actions";
                actionsDiv.innerHTML = `
                    <button class="action-btn thumbs-up" title="Good response">
                        <i class="far fa-thumbs-up"></i>
                    </button>
                    <button class="action-btn thumbs-down" title="Bad response">
                        <i class="far fa-thumbs-down"></i>
                    </button>
                    <button class="action-btn regenerate-btn" title="Regenerate response" data-original="${originalMessage}">
                        <i class="fas fa-redo"></i> Regenerate
                    </button>
                    <button class="action-btn copy-btn" title="Copy response">
                        <i class="far fa-copy"></i> Copy
                    </button>
                    <button class="action-btn clear-history-btn" title="Clear chat history">
                        <i class="fas fa-trash"></i> Clear History
                    </button>
                `;
                contentDiv.appendChild(actionsDiv);
            }

            messageDiv.appendChild(contentDiv);
            chatWindow.appendChild(messageDiv);
            chatWindow.scrollTop = chatWindow.scrollHeight;

            // Save to history only if not loading from history initially
            if (document.querySelectorAll('.message').length > (JSON.parse(localStorage.getItem(`chatHistory_${sessionId}`)) || []).length) {
                saveMessage(role, content, originalMessage);
            }
        }

        function showTyping(show) {
            typingIndicator.style.display = show ? "flex" : "none";
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }

        async function sendMessage(e) {
            if (e) e.preventDefault();
            const message = userInput.value.trim();
            if (!message) return;

            appendMessage("user", message);
            userInput.value = "";
            showTyping(true);
            await generateBotMessage(message);
            showTyping(false);
        }

        async function generateBotMessage(message) {
            try {
                const response = await fetch("http://localhost:5000/chat", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ message, session_id: sessionId })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();

                if (data.reply) {
                    appendMessage("bot", data.reply, message);
                } else if (data.error) {
                    appendMessage("bot", `Oops! ${data.error} ${data.details || ''}`);
                    console.error("Bot error:", data.details || data.error);
                } else {
                    appendMessage("bot", "Sorry, I didn't understand that.");
                }
            } catch (error) {
                console.error("Fetch error:", error);
                appendMessage("bot", "Can't reach the server. Check your connection or try again later.");
            }
        }

        // Load history on page load
        loadChatHistory();

        // Event Listeners
        sendBtn.addEventListener("click", sendMessage);
        userInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") sendMessage(e);
        });

        chatWindow.addEventListener("click", (e) => {
            if (e.target.closest(".thumbs-up")) {
                const btn = e.target.closest(".thumbs-up");
                btn.innerHTML = '<i class="fas fa-thumbs-up"></i>';
                btn.style.color = "var(--primary-color)";
            }

            if (e.target.closest(".thumbs-down")) {
                const btn = e.target.closest(".thumbs-down");
                btn.innerHTML = '<i class="fas fa-thumbs-down"></i>';
                btn.style.color = "#ef4444";
            }

            if (e.target.closest(".regenerate-btn")) {
                const originalMessage = e.target.closest(".regenerate-btn").dataset.original;
                if (originalMessage) {
                    showTyping(true);
                    generateBotMessage(originalMessage).then(() => showTyping(false));
                }
            }

            if (e.target.closest(".copy-btn")) {
                const btn = e.target.closest(".copy-btn");
                const messageContent = btn.closest(".message-content").innerHTML;
                const tempDiv = document.createElement("div");
                tempDiv.innerHTML = messageContent;
                const plainText = tempDiv.textContent || tempDiv.innerText || "";

                navigator.clipboard.writeText(plainText).then(() => {
                    btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    btn.classList.add("copied");
                    setTimeout(() => {
                        btn.innerHTML = '<i class="far fa-copy"></i> Copy';
                        btn.classList.remove("copied");
                    }, 2000);
                }).catch(err => {
                    console.error("Failed to copy:", err);
                    btn.innerHTML = '<i class="fas fa-times"></i> Error';
                    setTimeout(() => {
                        btn.innerHTML = '<i class="far fa-copy"></i> Copy';
                    }, 2000);
                });
            }

            if (e.target.closest(".clear-history-btn")) {
                if (confirm("Are you sure you want to clear your chat history?")) {
                    clearChatHistory();
                }
            }
        });
    });
    </script>
</body>