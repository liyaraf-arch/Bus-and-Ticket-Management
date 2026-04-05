<?php
require_once '../includes/config.php';
$page_title = 'FAQ';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .page-container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .page-header h1 {
            color: white;
            margin-bottom: 10px;
        }
        
        .page-content {
            padding: 40px;
        }
        
        .faq-item {
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .faq-question {
            background: #f8f9fa;
            padding: 15px 20px;
            cursor: pointer;
            font-weight: bold;
            color: var(--dark-green);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .faq-question:hover {
            background: #e8f5e9;
        }
        
        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            color: #555;
            line-height: 1.6;
        }
        
        .faq-item.active .faq-answer {
            padding: 20px;
            max-height: 500px;
        }
        
        .faq-question i {
            transition: transform 0.3s ease;
        }
        
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        
        .btn-home {
            display: inline-block;
            background: var(--primary-green);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 30px;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-container">
            <div class="page-header">
                <h1><i class="fas fa-question-circle"></i> Frequently Asked Questions</h1>
                <p>Find answers to common questions</p>
            </div>
            
            <div class="page-content">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>How do I book a bus ticket?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        To book a ticket, simply select your journey details (from, to, date), search for available buses, select your seats, and complete the payment. Your ticket will be generated instantly.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What payment methods do you accept?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        We accept bKash, Nagad, and all major credit cards (Visa, Mastercard). All payments are processed securely.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Can I cancel my booking?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Yes, you can cancel your booking up to 2 hours before departure. Cancellation fees may apply. Please check our cancellation policy for details.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>How do I get my ticket after payment?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        After successful payment, you can download your ticket as PDF instantly. You will also receive a confirmation email with your ticket details.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Is my payment information secure?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Yes, all payments are processed through secure, encrypted connections. We never store your credit card information on our servers.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>How can I contact customer support?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        You can reach our customer support team 24/7 at +880 16374 16374 or email us at support@trotter.com. You can also use the live chat feature on our website.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What if my bus is delayed or cancelled?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        In case of bus delays or cancellations, we will notify you via SMS and email. For cancellations, you are eligible for a full refund. Please contact our support team for assistance.
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <a href="../index.php" class="btn-home">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleFAQ(element) {
            const faqItem = element.closest('.faq-item');
            faqItem.classList.toggle('active');
        }
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>