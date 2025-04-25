<?php
session_start();
$ROOT = "/ai-roadtrip-planner/frontend";
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . "$ROOT/includes/header.php"; ?>

<!-- Styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-color: #4c63ce;
        --primary-dark: #3b4fa3;
        --secondary-color: #10a37f;
        --text-color: #2d3748;
        --light-gray: #f7fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --white: #ffffff;
        --road-yellow: #f6e05e;
        --nature-green: #48bb78;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        color: var(--text-color);
        background-color: var(--light-gray);
        background-image: url('https://images.unsplash.com/photo-1501594907352-04cda38ebc29?q=80&w=2832&auto=format&fit=crop');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding-top: 70px;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        z-index: -1;
    }

    .chat-container {
        display: flex;
        flex-direction: column;
        height: 80vh;
        width: 100%;
        max-width: 1000px;
        margin: 2rem auto;
        background: linear-gradient(to bottom, var(--white), var(--light-gray));
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        border-radius: 1.25rem;
        overflow: hidden;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid var(--medium-gray);
    }

    .chat-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.2);
    }

    .chat-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--medium-gray);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
        color: var(--white);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .chat-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .chat-icon {
        font-size: 2rem;
    }

    .chat-main {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        background: rgba(247, 250, 252, 0.85);
    }

    .message {
        max-width: 800px;
        margin: 0 auto;
        width: 100%;
        animation: fadeIn 0.4s ease-out forwards;
    }

    .user-message {
        align-self: flex-end;
        background: var(--primary-dark);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 1.25rem 1.25rem 0 1.25rem;
        max-width: 80%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .bot-message {
        background: var(--white);
        padding: 1.5rem;
        border-radius: 1.25rem 1.25rem 1.25rem 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--medium-gray);
        max-width: 80%;
    }

    .bot-message h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0 0 0.75rem 0;
        font-family: 'Poppins', sans-serif;
    }

    .bot-message .bg-light {
        background: rgba(247, 250, 252, 0.95);
        border-radius: 0.75rem;
        padding: 1.25rem;
        border: 1px solid var(--medium-gray);
        line-height: 1.7;
    }

    .message-content {
        line-height: 1.7;
    }

    .message-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.25rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .action-btn {
        background: var(--light-gray);
        border: none;
        color: var(--dark-gray);
        cursor: pointer;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: var(--medium-gray);
        transform: translateY(-1px);
    }

    .thumbs-up:hover { color: var(--secondary-color); }
    .thumbs-down:hover { color: #e53e3e; }
    .regenerate-btn, .copy-btn, .clear-history-btn { color: var(--primary-color); }
    .copy-btn.copied { color: var(--primary-dark); }
    .clear-history-btn { margin-left: auto; }

    .chat-input-container {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--medium-gray);
        background: var(--white);
        position: sticky;
        bottom: 0;
    }

    .chat-input-wrapper {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        display: flex;
        align-items: center;
    }

    .chat-input {
        width: 100%;
        padding: 1rem 4.5rem 1rem 1.5rem;
        border: 1px solid var(--medium-gray);
        border-radius: 2.5rem;
        font-size: 1rem;
        outline: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .chat-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(76, 99, 206, 0.25);
        transform: scale(1.01);
    }

    .send-btn {
        position: absolute;
        right: 1rem;
        background: var(--primary-color);
        border: none;
        color: white;
        cursor: pointer;
        font-size: 1.25rem;
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .send-btn:hover {
        background: var(--primary-dark);
        transform: scale(1.1);
    }

    .typing-indicator {
        display: none;
        padding: 1.25rem 0;
        max-width: 800px;
        margin: 0 auto;
    }

    .typing-dots {
        display: flex;
        gap: 0.5rem;
    }

    .typing-dot {
        width: 0.75rem;
        height: 0.75rem;
        background: var(--primary-color);
        border-radius: 50%;
        opacity: 0.4;
        animation: typingAnimation 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typingAnimation {
        0%, 60%, 100% { opacity: 0.4; transform: translateY(0); }
        30% { opacity: 1; transform: translateY(-5px); }
    }

    .bot-response-list {
        list-style-type: none;
        padding-left: 0;
        margin: 0;
    }

    .bot-response-list li {
        padding-left: 1.75rem;
        position: relative;
        margin-bottom: 0.75rem;
        line-height: 1.6;
    }

    .bot-response-list li:before {
        content: "→";
        position: absolute;
        left: 0;
        color: var(--primary-color);
        font-weight: bold;
    }

    .route-highlight { color: var(--primary-color); font-weight: 600; }
    .accommodation-highlight { color: var(--secondary-color); font-weight: 600; }

    .login-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: calc(100vh - 80px);
        padding: 2.5rem;
        text-align: center;
        background: linear-gradient(to bottom, var(--white), var(--light-gray));
        border-radius: 1.5rem;
        max-width: 600px;
        margin: 2rem auto;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .login-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.75rem;
        font-weight: 700;
        margin-bottom: 1.75rem;
        color: var(--primary-color);
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .login-subtitle {
        font-size: 1.25rem;
        color: var(--text-color);
        margin-bottom: 2.5rem;
        max-width: 500px;
        line-height: 1.7;
    }

    .login-btn, .signup-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 2.5rem;
        border-radius: 2.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        margin: 0 1rem;
        font-family: 'Poppins', sans-serif;
        min-width: 160px;
    }

    .login-btn {
        background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 8px rgba(76, 99, 206, 0.25);
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(76, 99, 206, 0.35);
    }

    .signup-btn {
        background: var(--white);
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .signup-btn:hover {
        background: rgba(76, 99, 206, 0.1);
        transform: translateY(-2px);
    }

    .login-buttons {
        display: flex;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .feature-icon {
        font-size: 3.5rem;
        color: var(--primary-color);
        margin-bottom: 1.75rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .footer {
        text-align: center;
        padding: 1rem;
        background: var(--white);
        border-top: 1px solid var(--medium-gray);
        color: var(--dark-gray);
        font-size: 0.875rem;
    }

    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .spinner {
        width: 2rem;
        height: 2rem;
        border: 4px solid var(--primary-color);
        border-top: 4px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .chat-container {
            height: calc(100vh - 120px);
            margin: 1rem auto;
            border-radius: 0.75rem;
        }
        
        .user-message, .bot-message {
            max-width: 90%;
        }
        
        .login-buttons {
            flex-direction: column;
            gap: 1rem;
        }
        
        .login-btn, .signup-btn {
            width: 100%;
            margin: 0;
        }
    }

    /* Trip details */
    .trip-details {
        background: rgba(76, 99, 206, 0.05);
        border-left: 4px solid var(--primary-color);
        padding: 1.25rem;
        border-radius: 0 0.75rem 0.75rem 0;
        margin: 1rem 0;
    }

    .trip-summary {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
    }

    .trip-stop {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .trip-stop:before {
        content: "📍";
        margin-right: 0.5rem;
    }

    .weather-info {
        display: inline-flex;
        align-items: center;
        background: rgba(72, 187, 120, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 1.25rem;
        margin-top: 0.75rem;
        font-size: 0.875rem;
    }

    .weather-info:before {
        content: "☀️";
        margin-right: 0.5rem;
    }
</style>

<body>
    <!-- Main content based on user authentication -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="chat-container">
            <!-- Chat header with TravelBot branding -->
            <header class="chat-header">
                <div class="chat-title">
                    <i class="fas fa-route chat-icon"></i>
                    <span>TravelBot: AI Road Trip Planner</span>
                </div>
            </header>
            
            <!-- Loading spinner for chat history -->
            <div id="loading-spinner" class="loading-spinner">
                <div class="spinner"></div>
            </div>
            
            <!-- Chat messages -->
            <main class="chat-main" id="chat-window" style="display: none;">
                <!-- Chat history will be loaded here -->
            </main>
            
            <!-- Typing indicator -->
            <div id="typing-indicator" class="typing-indicator">
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
            
            <!-- Chat input -->
            <div class="chat-input-container">
                <div class="chat-input-wrapper">
                    <input type="text" class="chat-input" id="user-input" placeholder="Plan a trip, check weather, or explore destinations..." />
                    <button class="send-btn" id="send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Footer with developer credits -->
        <footer class="footer">
            Powered by TravelBot, developed by Vanshika Babral for AI-driven travel planning.
        </footer>
    <?php else: ?>
        <div class="login-container">
            <i class="fas fa-route feature-icon"></i>
            <h1 class="login-title">TravelBot: AI Road Trip Planner</h1>
            <p class="login-subtitle">Embark on unforgettable journeys with personalized road trip plans, scenic routes, and local gems, all powered by TravelBot’s AI.</p>
            <div class="login-buttons">
                <a href="<?= $ROOT ?>/login.php" class="login-btn">
                    <i class="fas fa-sign-in-alt mr-2"></i> Start Your Journey
                </a>
                <a href="<?= $ROOT ?>/signup.php" class="signup-btn">
                    <i class="fas fa-user-plus mr-2"></i> Join the Adventure
                </a>
            </div>
        </div>
    <?php endif; ?>

    <script>
    // Initialize chat functionality on page load
    document.addEventListener('DOMContentLoaded', function () {
        // Session and DOM elements
        const sessionId = "user_<?php echo $_SESSION['user_id'] ?? 'guest'; ?>";
        const chatWindow = document.getElementById("chat-window");
        const userInput = document.getElementById("user-input");
        const sendBtn = document.getElementById("send-btn");
        const typingIndicator = document.getElementById("typing-indicator");
        const loadingSpinner = document.getElementById("loading-spinner");

        // Welcome message with TravelBot branding
        const welcomeMessage = `
            <div class="welcome-message">
                <p>🚗 <strong>Welcome to TravelBot!</strong> I'm your AI travel assistant, created by Vanshika Babral, to plan epic road trips!</p>
                <ul class="bot-response-list">
                    <li>Plan <span class="route-highlight">scenic routes</span> to your dream destinations</li>
                    <li>Discover <span class="route-highlight">hidden gems</span> and must-see stops</li>
                    <li>Find <span class="accommodation-highlight">hotels, campgrounds, and dining</span> tailored to you</li>
                    <li>Get <span class="weather-info">weather updates</span> and road conditions</li>
                    <li>Estimate <span class="route-highlight">travel times</span> and distances</li>
                </ul>
                <div class="trip-details">
                    <div class="trip-summary">Try asking:</div>
                    <div class="trip-stop">"Plan a 3-day trip from San Francisco to Los Angeles"</div>
                    <div class="trip-stop">"Best scenic route from Chicago to Denver"</div>
                    <div class="trip-stop">"Family-friendly stops between Dallas and Austin"</div>
                </div>
                <p>Where’s your next adventure? Let’s hit the road!</p>
            </div>
        `;

        // Load chat history from localStorage
        function loadChatHistory() {
            loadingSpinner.style.display = 'flex';
            chatWindow.style.display = 'none';
            setTimeout(() => { // Simulate loading delay
                const history = JSON.parse(localStorage.getItem(`chatHistory_${sessionId}`)) || [];
                if (history.length === 0) {
                    appendMessage("bot", welcomeMessage);
                } else {
                    history.forEach(msg => {
                        appendMessage(msg.role, msg.content, msg.originalMessage || '');
                    });
                }
                loadingSpinner.style.display = 'none';
                chatWindow.style.display = 'flex';
            }, 500);
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
            appendMessage("bot", welcomeMessage);
        }

        // Append message to chat window
        function appendMessage(role, content, originalMessage = '') {
            const messageDiv = document.createElement("div");
            messageDiv.className = `message ${role}-message`;

            const contentDiv = document.createElement("div");
            contentDiv.className = "message-content";
            contentDiv.innerHTML = content.trim();

            if (role === "bot") {
                const actionsDiv = document.createElement("div");
                actionsDiv.className = "message-actions";
                actionsDiv.innerHTML = `
                    <button class="action-btn thumbs-up" title="Good response">
                        <i class="far fa-thumbs-up"></i> Helpful
                    </button>
                    <button class="action-btn thumbs-down" title="Bad response">
                        <i class="far fa-thumbs-down"></i> Not Helpful
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

            if (document.querySelectorAll('.message').length > (JSON.parse(localStorage.getItem(`chatHistory_${sessionId}`)) || []).length) {
                saveMessage(role, content, originalMessage);
            }
        }

        // Show/hide typing indicator
        function showTyping(show) {
            typingIndicator.style.display = show ? "flex" : "none";
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }

        // Send message to backend
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

        // Fetch bot response
        async function generateBotMessage(message) {
            try {
                const response = await fetch("http://localhost:5000/chat", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ 
                        message, 
                        session_id: sessionId,
                        preferences: {
                            trip_type: "road",
                            interests: "scenic,attractions,food"
                        }
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                console.log("Backend response:", data); // Add debug logging
                if (data.response) {
                    // Ensure proper rendering of HTML structure from backend
                    let formattedResponse = data.response;
                    if (formattedResponse.includes("<h2>")) {
                        formattedResponse = formattedResponse.replace(/<h2>/g, '<h2>').replace(/<\/h2>/g, '</h2>');
                    }
                    appendMessage("bot", formattedResponse, message);
                } else if (data.error) {
                    appendMessage("bot", `<div class="bg-light p-3 my-2">🚧 Error: ${data.error} (Status: ${response.status})</div>`);
                } else {
                    appendMessage("bot", `<div class="bg-light p-3 my-2">⚠️ Unexpected response from TravelBot. Debug: ${JSON.stringify(data)}</div>`);
                }
            } catch (error) {
                console.error("Fetch error:", error);
                appendMessage("bot", `<div class="bg-light p-3 my-2">⚠️ Can't reach TravelBot. Check your connection. Error: ${error.message}</div>`);
            }
        }

        // Load history on page load
        loadChatHistory();

        // Event listeners
        sendBtn.addEventListener("click", sendMessage);
        userInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") sendMessage(e);
        });

        chatWindow.addEventListener("click", (e) => {
            if (e.target.closest(".thumbs-up")) {
                const btn = e.target.closest(".thumbs-up");
                btn.innerHTML = '<i class="fas fa-thumbs-up"></i> Helpful';
                btn.style.color = "var(--secondary-color)";
            }

            if (e.target.closest(".thumbs-down")) {
                const btn = e.target.closest(".thumbs-down");
                btn.innerHTML = '<i class="fas fa-thumbs-down"></i> Not Helpful';
                btn.style.color = "#e53e3e";
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
                const messageContent = btn.closest(".message-content");
                const bgLight = messageContent.querySelector(".bg-light");
                const plainText = bgLight ? bgLight.textContent : messageContent.textContent || messageContent.innerText || "";

                navigator.clipboard.writeText(plainText.trim()).then(() => {
                    btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    btn.classList.add("copied");
                    setTimeout(() => {
                        btn.innerHTML = '<i class="far fa-copy"></i> Copy';
                        btn.classList.remove("copied");
                    }, 2000);
                }).catch(err => {
                    btn.innerHTML = '<i class="fas fa-times"></i> Error';
                    setTimeout(() => {
                        btn.innerHTML = '<i class="far fa-copy"></i> Copy';
                    }, 2000);
                });
            }

            if (e.target.closest(".clear-history-btn")) {
                if (confirm("Are you sure you want to clear your trip planning history?")) {
                    clearChatHistory();
                }
            }
        });

        // Focus input on load
        if (userInput) {
            userInput.focus();
        }
    });
    </script>
</body>