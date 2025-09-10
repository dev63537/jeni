<?php
session_start();
require 'config.php';

$message = '';
$message_type = '';
$selected_service = isset($_GET['service']) ? $_GET['service'] : '';

// Service options mapping
$services = [
    'flexboard' => 'Flex Board Banner',
    'vinyl' => 'Vinyl Sticker Printing',
    'oneway' => 'One-Way Vision Print',
    'reflective' => 'Reflective Vinyl Print',
    'ecohd' => 'Eco HD Print',
    'lighting' => 'Lighting Board',
    'rollup' => 'Roll-Up Standee',
    'canopy' => 'Canopy',
    'ledboard' => 'L.E.D Board',
    'safety' => 'Industrial Safety Sign Board',
    'acp' => 'A.C.P Board',
    'foam' => 'Foam Board',
    'visiting_card' => 'Visiting Cards',
    'letterhead' => 'Letter Head',
    'billbook' => 'Bill Book',
    'envelope' => 'Envelope',
    'brochure' => 'Brochure',
    'pamphlet' => 'Pamphlet',
    'idcard' => 'ID Card',
    'stickers' => 'Stickers',
    'invitation' => 'Invitation Card'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $service_type = $_POST['service_type'];
    $quantity = $_POST['quantity'];
    $size = trim($_POST['size']);
    $description = trim($_POST['description']);
    $urgent = isset($_POST['urgent']) ? 1 : 0;
    
    // Basic validation
    if (empty($customer_name) || empty($email) || empty($phone) || empty($service_type)) {
        $message = "Please fill in all required fields.";
        $message_type = 'error';
    } else {
        // Insert order into database (you'll need to create this table)
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, email, phone, service_type, quantity, size, description, urgent_order, order_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending')");
        $stmt->bind_param("sssssssi", $customer_name, $email, $phone, $service_type, $quantity, $size, $description, $urgent);
        
        if ($stmt->execute()) {
            $order_id = $conn->insert_id;
            $message = "Order submitted successfully! Order ID: #" . $order_id . ". We will contact you soon.";
            $message_type = 'success';
            
            // Clear form fields on success
            $customer_name = $email = $phone = $service_type = $quantity = $size = $description = '';
            $urgent = 0;
        } else {
            $message = "Error submitting order. Please try again.";
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
    <title>Order Now - Shyam Enterprise</title>
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
                <li class="nav-item"><a href="order.php" class="nav-link active">Order Now</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>

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
            <h1>Place Your Order</h1>
            <p>Get a custom quote for your printing and designing needs</p>
        </div>

        <div class="order-form-container">
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="order.php" method="POST" class="order-form">
                <div class="form-section">
                    <h3>📋 Order Details</h3>
                    
                    <div class="form-group">
                        <label for="service_type">Service Type *</label>
                        <select name="service_type" id="service_type" required>
                            <option value="">Select a service</option>
                            <?php foreach ($services as $key => $service_name): ?>
                                <option value="<?php echo $key; ?>" 
                                    <?php echo ($selected_service == $key || (isset($_POST['service_type']) && $_POST['service_type'] == $key)) ? 'selected' : ''; ?>>
                                    <?php echo $service_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" min="1" 
                                value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '1'; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="size">Size/Dimensions</label>
                            <input type="text" name="size" id="size" placeholder="e.g., 3x2 feet, A4, Custom" 
                                value="<?php echo isset($_POST['size']) ? htmlspecialchars($_POST['size']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description & Requirements</label>
                        <textarea name="description" id="description" rows="4" 
                            placeholder="Describe your requirements, colors, text, design preferences, etc."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="urgent" value="1" <?php echo (isset($_POST['urgent']) && $_POST['urgent']) ? 'checked' : ''; ?>>
                            <span class="checkmark">⚡</span>
                            Urgent Order (Additional charges may apply)
                        </label>
                    </div>
                </div>

                <div class="form-section">
                    <h3>👤 Contact Information</h3>
                    
                    <div class="form-group">
                        <label for="customer_name">Full Name *</label>
                        <input type="text" name="customer_name" id="customer_name" required 
                            value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>">
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
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        🚀 Submit Order Request
                    </button>
                    <p class="form-note">
                        📞 We'll contact you within 24 hours with a detailed quote
                    </p>
                </div>
            </form>
        </div>

        <!-- Contact Info Sidebar -->
        <div class="contact-info">
            <h3>📞 Quick Contact</h3>
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
            
            <div class="contact-item">
                <span class="contact-icon">📍</span>
                <div>
                    <strong>Address</strong>
                    <p>Shop No.3, Indraprasth Complex, Nr.Pratik Chowkdi, G.I.D.C, Ankleshwar-393002</p>
                </div>
            </div>
            
            <div class="business-hours">
                <h4>🕒 Business Hours</h4>
                <p>Monday - Saturday: 9:00 AM - 8:00 PM</p>
                <p>Sunday: 10:00 AM - 6:00 PM</p>
            </div>
        </div>
    </div>

</body>

</html>