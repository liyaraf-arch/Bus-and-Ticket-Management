<?php
require_once '../includes/config.php';
$page_title = 'Contact Us';
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
        
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
        
        .contact-card {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .contact-card i {
            font-size: 40px;
            color: var(--primary-green);
            margin-bottom: 15px;
        }
        
        .contact-card h3 {
            color: var(--dark-green);
            margin-bottom: 10px;
        }
        
        .contact-form {
            margin-top: 40px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-green);
        }
        
        .btn-submit {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
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
        
        .map {
            margin: 30px 0;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-container">
            <div class="page-header">
                <h1><i class="fas fa-envelope"></i> Contact Us</h1>
                <p>We'd love to hear from you</p>
            </div>
            
            <div class="page-content">
                <div class="contact-grid">
                    <div class="contact-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Office Address</h3>
                        <p>J-211, Munshipara, Gazipur Sadar,<br>Gazipur 1700, Bangladesh</p>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-phone"></i>
                        <h3>Phone Number</h3>
                        <p>+880 1518925098<br>+880 1848599651</p>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-envelope"></i>
                        <h3>Email Address</h3>
                        <p>support@trotter.com<br>info@trotter.com</p>
                    </div>
                </div>
                
                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.123456!2d90.381234!3d23.745678!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDQ0JzQ0LjQiTiA5MMKwMjInNTguMCJF!5e0!3m2!1sen!2sbd!4v1234567890" 
                            width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
                
                <div class="contact-form">
                    <h3 style="color: var(--dark-green); margin-bottom: 20px;">Send us a Message</h3>
                    <form action="contact-process.php" method="POST">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Subject">
                        </div>
                        <div class="form-group">
                            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Send Message</button>
                    </form>
                </div>
                
                <div style="text-align: center;">
                    <a href="../index.php" class="btn-home">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>