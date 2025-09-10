<?php
session_start();
require 'config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message_text = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message_text)) {
        $message = "Please fill in all required fields.";
        $message_type = 'error';
    } else {
        // Insert contact message into database
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message_text);
        
        if ($stmt->execute()) {
            $message = "Thank you for your message! We will get back to you within 24 hours.";
            $message_type = 'success';
            
            // Clear form fields on success
            $name = $email = $phone = $subject = $message_text = '';
        } else {
            $message = "Error sending message. Please try again.";
            $message_type = 'error';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Contact Us - Shyam Enterprise</title>
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/order.css" />
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">Shyam Enterprise</a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
                <li class="nav-item"><a href="order.php" class="nav-link">Order Now</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link active">Contact</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="signup.php" class="nav-link">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="order-container">
        <div class="order-header">
            <h1>Contact Us</h1>
            <p>Get in touch with us for any inquiries or support</p>
        </div>

        <div class="order-form-container">
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="contact.php" method="POST" class="order-form">
                <div class="form-section">
                    <h3>📝 Contact Information</h3>
                    
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" name="name" id="name" required 
                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" name="email" id="email" required 
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" name="phone" id="phone" required 
                                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" name="subject" id="subject" required 
                            placeholder="What is this regarding?" 
                            value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea name="message" id="message" rows="6" required 
                            placeholder="Please describe your inquiry in detail..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        📤 Send Message
                    </button>
                    <p class="form-note">
                        📞 We'll respond to your message within 24 hours
                    </p>
                </div>
            </form>
        </div>

        <!-- Contact Info Sidebar -->
        <div class="contact-info">
            <h3>📍 Our Location</h3>
            
            <div class="contact-item">
                <span class="contact-icon">📍</span>
                <div>
                    <strong>Address</strong>
                    <p>Shop No.3, Indraprasth Complex,<br>
                    Nr.Pratik Chowkdi, G.I.D.C,<br>
                    Ankleshwar-393002, Gujarat</p>
                </div>
            </div>
            
            <div class="contact-item">
                <span class="contact-icon">📱</span>
                <div>
                    <strong>Phone</strong>
                    <p>+91 96013 40892</p>
                </div>
            </div>
            
            <div class="contact-item">
                <span class="contact-icon">📧</span>
                <div>
                    <strong>Email</strong>
                    <p>shyamenterprise888@gmail.com</p>
                </div>
            </div>
            
            <div class="business-hours">
                <h4>🕒 Business Hours</h4>
                <p>Monday - Saturday: 9:00 AM - 8:00 PM</p>
                <p>Sunday: 10:00 AM - 6:00 PM</p>
            </div>

            <div class="business-hours" style="background: #27ae60; margin-top: 20px;">
                <h4>🚚 Service Areas</h4>
                <p>Ankleshwar & Surrounding Areas</p>
                <p>Gujarat State</p>
                <p>Pan India Delivery Available</p>
            </div>
        </div>
    </div>

</body>

</html>
