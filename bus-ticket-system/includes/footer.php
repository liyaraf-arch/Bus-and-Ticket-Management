<!-- includes/footer.php - Complete Updated File -->

<!-- Footer Section -->
<footer style="background: #2c3e50; color: white; margin-top: 50px; padding: 40px 0 20px;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
            <!-- About Section -->
            <div>
                <h3 style="color: var(--primary-green); margin-bottom: 20px;">About <?php echo SITE_NAME; ?></h3>
                <p style="line-height: 1.6; color: #ecf0f1;"><?php echo SITE_NAME; ?> is Bangladesh's leading online ticketing platform. We provide bus ticket bookings with ease and security.</p>
                <div style="margin-top: 20px;">
                    <a href="#" style="color: white; margin-right: 15px; font-size: 20px;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: white; margin-right: 15px; font-size: 20px;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: white; margin-right: 15px; font-size: 20px;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: white; font-size: 20px;"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 style="color: var(--primary-green); margin-bottom: 20px;">Quick Links</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><a href="<?php echo SITE_URL; ?>pages/about.php" style="color: #ecf0f1; text-decoration: none;">About Us</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?php echo SITE_URL; ?>pages/contact.php" style="color: #ecf0f1; text-decoration: none;">Contact Us</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?php echo SITE_URL; ?>pages/terms.php" style="color: #ecf0f1; text-decoration: none;">Terms & Conditions</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?php echo SITE_URL; ?>pages/privacy.php" style="color: #ecf0f1; text-decoration: none;">Privacy Policy</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?php echo SITE_URL; ?>pages/faq.php" style="color: #ecf0f1; text-decoration: none;">FAQ</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 style="color: var(--primary-green); margin-bottom: 20px;">Contact Info</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary-green);"></i>
                        <span style="color: #ecf0f1;">J-211, Munshipara, Gazipur Sadar, Gazipur 1700</span>
                    </li>
                    <li style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-phone" style="color: var(--primary-green);"></i>
                        <span style="color: #ecf0f1;">+880 1848599651</span>
                    </li>
                    <li style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-envelope" style="color: var(--primary-green);"></i>
                        <span style="color: #ecf0f1;">support@trotter.com</span>
                    </li>
                </ul>
            </div>
            
            <!-- Download App -->
            <div>
                <h3 style="color: var(--primary-green); margin-bottom: 20px;">Download App</h3>
                <p style="color: #ecf0f1; margin-bottom: 15px;">Get the <?php echo SITE_NAME; ?> app for the best experience</p>
                <div style="display: flex; gap: 10px;">
                    <a href="#" style="display: inline-block;">
                        <img src="<?php echo SITE_URL; ?>assets/images/google-play.png" alt="Google Play" style="height: 40px;">
                    </a>
                    <a href="#" style="display: inline-block;">
                        <img src="<?php echo SITE_URL; ?>assets/images/app-store.png" alt="App Store" style="height: 40px;">
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div style="border-top: 1px solid #34495e; margin-top: 40px; padding-top: 20px; text-align: center; color: #95a5a6;">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved. | Version 2.0</p>
            <p style="margin-top: 10px; font-size: 14px;">
                <i class="fas fa-lock" style="color: var(--primary-green);"></i> 
                Secure payment | 
                <i class="fas fa-shield-alt" style="color: var(--primary-green);"></i> 
                100% safe booking
            </p>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" style="display: none; position: fixed; bottom: 30px; right: 30px; background: var(--primary-green); color: white; border: none; border-radius: 50%; width: 50px; height: 50px; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 999;">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- JavaScript Files -->
<script src="<?php echo SITE_URL; ?>assets/js/main.js"></script>

<!-- Additional Scripts -->
<script>
    // Back to Top functionality
    window.onscroll = function() {
        const backToTop = document.getElementById('backToTop');
        if(backToTop) {
            if(document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        }
    };
    
    document.getElementById('backToTop')?.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Mobile Menu Toggle
    document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobileMenu');
        if(mobileMenu.style.display === 'none' || !mobileMenu.style.display) {
            mobileMenu.style.display = 'block';
        } else {
            mobileMenu.style.display = 'none';
        }
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>

<!-- Smart Chat Widget -->
<div class="chat-widget">
    <div class="chat-toggle" onclick="toggleChat()">
        <i class="fas fa-comment-dots"></i>
    </div>
    
    <div class="chat-box" id="chatBox">
        <div class="chat-header">
            <i class="fas fa-headset"></i>
            <h4>Customer Support</h4>
            <span class="online-status">Online</span>
            <i class="fas fa-times chat-close" onclick="toggleChat()"></i>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-bubble">
                    Hello! 👋<br>
                    Welcome to <?php echo SITE_NAME; ?> Support.<br>
                    How can I help you today?
                </div>
            </div>
        </div>
        
        <div class="quick-replies" id="quickReplies">
            <button class="quick-reply-btn" onclick="sendQuickReply('How to book a ticket?')">🎫 How to book?</button>
            <button class="quick-reply-btn" onclick="sendQuickReply('Payment methods?')">💳 Payment methods</button>
            <button class="quick-reply-btn" onclick="sendQuickReply('Cancel booking?')">❌ Cancel booking</button>
            <button class="quick-reply-btn" onclick="sendQuickReply('Refund policy?')">💰 Refund policy</button>
            <button class="quick-reply-btn" onclick="sendQuickReply('Contact support')">📞 Contact support</button>
        </div>
        
        <div class="chat-input-area">
            <input type="text" id="chatInput" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<style>
    /* Chat Widget Styles */
    .chat-widget {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }
    
    .chat-toggle {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 5px 20px rgba(46, 204, 113, 0.4);
        transition: all 0.3s ease;
    }
    
    .chat-toggle:hover {
        transform: scale(1.1);
    }
    
    .chat-toggle i {
        font-size: 28px;
        color: white;
    }
    
    .chat-box {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 350px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        overflow: hidden;
        display: none;
        animation: slideUp 0.3s ease;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .chat-header {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .chat-header i {
        font-size: 24px;
    }
    
    .chat-header h4 {
        flex: 1;
        margin: 0;
        color: white;
        font-size: 16px;
    }
    
    .chat-header .online-status {
        font-size: 10px;
        background: rgba(255,255,255,0.2);
        padding: 2px 8px;
        border-radius: 20px;
    }
    
    .chat-close {
        cursor: pointer;
        font-size: 20px;
    }
    
    .chat-messages {
        height: 350px;
        overflow-y: auto;
        padding: 15px;
        background: #f8f9fa;
    }
    
    .message {
        margin-bottom: 15px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .message.bot .message-avatar {
        width: 35px;
        height: 35px;
        background: #2ecc71;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .message.user {
        flex-direction: row-reverse;
    }
    
    .message.user .message-avatar {
        width: 35px;
        height: 35px;
        background: #3498db;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 18px;
        font-size: 13px;
        line-height: 1.4;
    }
    
    .message.bot .message-bubble {
        background: white;
        color: #333;
        border-radius: 18px 18px 18px 4px;
    }
    
    .message.user .message-bubble {
        background: #2ecc71;
        color: white;
        border-radius: 18px 18px 4px 18px;
    }
    
    .quick-replies {
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        border-top: 1px solid #eee;
    }
    
    .quick-reply-btn {
        background: #f0f0f0;
        border: none;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .quick-reply-btn:hover {
        background: #2ecc71;
        color: white;
    }
    
    .chat-input-area {
        display: flex;
        padding: 10px;
        border-top: 1px solid #eee;
        background: white;
    }
    
    .chat-input-area input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 25px;
        outline: none;
    }
    
    .chat-input-area button {
        background: #2ecc71;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 25px;
        margin-left: 8px;
        cursor: pointer;
    }
    
    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 10px 15px;
        background: white;
        border-radius: 18px;
        width: fit-content;
    }
    
    .typing-indicator span {
        width: 8px;
        height: 8px;
        background: #999;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }
    
    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }
    
    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }
    
    @keyframes typing {
        0%, 60%, 100% {
            transform: translateY(0);
            opacity: 0.5;
        }
        30% {
            transform: translateY(-10px);
            opacity: 1;
        }
    }
    
    @media (max-width: 480px) {
        .chat-box {
            width: 300px;
            right: -50px;
        }
        .chat-messages {
            height: 300px;
        }
    }
</style>

<script>
    function toggleChat() {
        const chatBox = document.getElementById('chatBox');
        if(chatBox.style.display === 'none' || !chatBox.style.display) {
            chatBox.style.display = 'block';
        } else {
            chatBox.style.display = 'none';
        }
    }
    
    function sendQuickReply(question) {
        document.getElementById('chatInput').value = question;
        sendMessage();
    }
    
    function handleKeyPress(event) {
        if(event.key === 'Enter') {
            sendMessage();
        }
    }
    
    function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        
        if(message === '') return;
        
        addMessage(message, 'user');
        input.value = '';
        
        showTypingIndicator();
        
        setTimeout(() => {
            removeTypingIndicator();
            const response = getBotResponse(message);
            addMessage(response, 'bot');
        }, 1000);
    }
    
    function addMessage(text, sender) {
        const messagesDiv = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        const avatar = sender === 'bot' ? '<i class="fas fa-robot"></i>' : '<i class="fas fa-user"></i>';
        
        messageDiv.innerHTML = `
            <div class="message-avatar">${avatar}</div>
            <div class="message-bubble">${text}</div>
        `;
        
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
    
    function showTypingIndicator() {
        const messagesDiv = document.getElementById('chatMessages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = `
            <div class="message-avatar"><i class="fas fa-robot"></i></div>
            <div class="typing-indicator">
                <span></span><span></span><span></span>
            </div>
        `;
        messagesDiv.appendChild(typingDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
    
    function removeTypingIndicator() {
        const typing = document.getElementById('typingIndicator');
        if(typing) typing.remove();
    }
    
    function getBotResponse(message) {
        const msg = message.toLowerCase();
        
        if(msg.includes('book') || msg.includes('ticket') || msg.includes('booking')) {
            return "🎫 To book a ticket:<br><br>1️⃣ Select your journey (From → To)<br>2️⃣ Choose travel date<br>3️⃣ Pick your preferred bus<br>4️⃣ Select seats<br>5️⃣ Complete payment<br><br>Your ticket will be generated instantly! ✅";
        }
        else if(msg.includes('payment') || msg.includes('pay') || msg.includes('method')) {
            return "💳 We accept multiple payment methods:<br><br>• bKash (Merchant Payment)<br>• Nagad<br>• Credit/Debit Cards (Visa/Mastercard)<br>• Pay Later option available!<br><br>All payments are 100% secure 🔒";
        }
        else if(msg.includes('cancel') || msg.includes('cancellation')) {
            return "❌ Cancellation Policy:<br><br>• Free cancellation up to 2 hours before departure<br>• 50% refund for cancellation less than 2 hours<br>• No refund for no-show<br><br>You can cancel from 'My Bookings' section.";
        }
        else if(msg.includes('refund')) {
            return "💰 Refund Policy:<br><br>• Refunds are processed within 5-7 business days<br>• Amount credited to original payment method<br>• For Pay Later bookings, refund after payment completion<br><br>Contact support for assistance!";
        }
        else if(msg.includes('contact') || msg.includes('support') || msg.includes('help')) {
            return "📞 Customer Support:<br><br>• Phone: +880 1848599651<br>• Email: support@trotter.com<br>• Live Chat: Available 24/7<br><br>We're here to help! 😊";
        }
        else if(msg.includes('price') || msg.includes('fare') || msg.includes('cost')) {
            return "💰 Fares vary by route and bus type:<br><br>• AC Buses: BDT 1000 - 1500<br>• Non-AC Buses: BDT 600 - 900<br>• AC Business: BDT 1500 - 2000<br><br>Check our website for exact fares!";
        }
        else if(msg.includes('seat')) {
            return "🪑 Seat Selection:<br><br>• You can select your preferred seats<br>• Window/Aisle seats available<br>• Ladies seats marked in pink<br>• Maximum 6 seats per booking<br><br>Select early for best seats!";
        }
        else if(msg.includes('account') || msg.includes('login') || msg.includes('register')) {
            return "👤 Account Help:<br><br>• Login: Use your email and password<br>• Register: Create new account for booking<br>• Forgot password? Click 'Forgot Password' link<br>• Update profile in 'My Profile' section";
        }
        else if(msg.includes('hello') || msg.includes('hi') || msg.includes('hey')) {
            return "Hello! 👋<br>How can I help you today? Feel free to ask about bookings, payments, cancellations, or anything else!";
        }
        else if(msg.includes('thank') || msg.includes('thanks')) {
            return "You're welcome! 😊<br>Is there anything else I can help you with?";
        }
        else {
            return "I'm here to help! 🤖<br><br>You can ask me about:<br>• Ticket booking process<br>• Payment methods<br>• Cancellation & refund<br>• Seat selection<br>• Account help<br><br>Or type your question above!";
        }
    }
</script>

</body>
</html>