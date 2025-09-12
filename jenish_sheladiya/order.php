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

// Common default sizes per service (can be edited later)
$default_sizes = [
    'flexboard' => '6x3 ft',
    'vinyl' => '1x1 ft',
    'oneway' => '4x3 ft',
    'reflective' => '2x2 ft',
    'ecohd' => '3x2 ft',
    'lighting' => '4x2 ft',
    'rollup' => '6x2 ft (standard)',
    'canopy' => '6x6 ft',
    'ledboard' => '3x1 ft',
    'safety' => '12x18 in',
    'acp' => '8x4 ft',
    'foam' => '3x2 ft',
    'visiting_card' => '3.5x2 in',
    'letterhead' => 'A4',
    'billbook' => 'A5',
    'envelope' => 'DL (220x110 mm)',
    'brochure' => 'A4 tri-fold',
    'pamphlet' => 'A5',
    'idcard' => '85x54 mm',
    'stickers' => '3x3 in',
    'invitation' => '7x5 in'
];

// Simple per-unit estimated pricing (can be refined later)
$unit_rates = [
    'flexboard' => 300,
    'vinyl' => 200,
    'oneway' => 500,
    'reflective' => 800,
    'ecohd' => 250,
    'lighting' => 3000,
    'rollup' => 1200,
    'canopy' => 2500,
    'ledboard' => 5000,
    'safety' => 400,
    'acp' => 2000,
    'foam' => 350,
    'visiting_card' => 200,
    'letterhead' => 400,
    'billbook' => 300,
    'envelope' => 250,
    'brochure' => 500,
    'pamphlet' => 300,
    'idcard' => 150,
    'stickers' => 100,
    'invitation' => 600,
];
$urgent_fee = 200;

// Prebuilt template options by service (remote images, no pricing)
/*
$template_options = [
   'flexboard' => [
        ['src' => 'https://images.unsplash.com/photo-1502899576159-f224dc2349fa?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1512295767273-ac109ac3acfa?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1520975916090-3105956dac38?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1529336953121-a2a9b9f0f3b0?w=800&auto=format&fit=crop']
    ],
    'vinyl' => [
        ['src' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1520975657038-7e0c9e1f0b3b?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1520975916090-3105956dac38?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1512295767273-ac109ac3acfa?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?w=800&auto=format&fit=crop']
    ],
    'default' => [
        ['src' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1520975916090-3105956dac38?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1502899576159-f224dc2349fa?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1520975657038-7e0c9e1f0b3b?w=800&auto=format&fit=crop'],
        ['src' => 'https://images.unsplash.com/photo-1529336953121-a2a9b9f0f3b0?w=800&auto=format&fit=crop']
    ]
];*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $service_type = $_POST['service_type'];
    $quantity = (int)$_POST['quantity'];
    $size = trim($_POST['size']);
    if ($size === '' && isset($default_sizes[$service_type])) { $size = $default_sizes[$service_type]; }
    $description = trim($_POST['description']);
    $urgent = isset($_POST['urgent']) ? 1 : 0;
    $selected_template = isset($_POST['selected_template']) ? trim($_POST['selected_template']) : '';
    $reference_image_path = null;

    // Optional image upload
    if (!empty($_FILES['reference_image']['name'])) {
        $uploads_dir = __DIR__ . '/uploads';
        if (!is_dir($uploads_dir)) { @mkdir($uploads_dir, 0777, true); }
        $ext = pathinfo($_FILES['reference_image']['name'], PATHINFO_EXTENSION);
        $safe_name = 'order_' . time() . '_' . rand(1000,9999) . '.' . strtolower($ext);
        $target = $uploads_dir . '/' . $safe_name;
        if (move_uploaded_file($_FILES['reference_image']['tmp_name'], $target)) {
            $reference_image_path = 'uploads/' . $safe_name; // relative path for web
        }
    }
    // If no upload, fall back to selected prebuilt template
    if (!$reference_image_path && $selected_template !== '') {
        // Allow templates from our list: either local under templates/ or remote http(s)
        if (strpos($selected_template, 'templates/') === 0 || preg_match('/^https?:\/\//i', $selected_template)) {
            $reference_image_path = $selected_template;
        }
    }
    
    // Basic validation
    if (empty($customer_name) || empty($email) || empty($phone) || empty($service_type)) {
        $message = "Please fill in all required fields.";
        $message_type = 'error';
    } else {
        // Server-side estimated amount
        $rate = isset($unit_rates[$service_type]) ? (float)$unit_rates[$service_type] : 0.0;
        if ($quantity < 1) { $quantity = 1; }
        $amount = ($rate * $quantity) + ($urgent ? $urgent_fee : 0);

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, email, phone, service_type, quantity, size, description, reference_image, urgent_order, amount, order_date, status, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending', 'unpaid')");
        $stmt->bind_param("ssssssssid", $customer_name, $email, $phone, $service_type, $quantity, $size, $description, $reference_image_path, $urgent, $amount);
        
        if ($stmt->execute()) {
            $order_id = $conn->insert_id;
            header('Location: checkout.php?order_id=' . $order_id);
            exit();
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
    <link rel="stylesheet" href="css/order_css.css" />
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
                    <?php 
                    $user_id = $_SESSION['user_id'];
                    $user_role = 'user';
                    try {
                        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
                        if ($stmt) {
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $user_data = $result->fetch_assoc();
                            $user_role = $user_data['role'] ?? 'user';
                            $stmt->close();
                        }
                    } catch (Exception $e) { $user_role = 'user'; }
                    ?>
                    <?php if (in_array($user_role, ['admin','manager'], true)): ?>
                        <li class="nav-item"><a href="admin_panel.php" class="nav-link">Admin Panel</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="signup.php" class="nav-link">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <header class="page-hero">
        <div class="container">
            <h1>Place Your Order</h1>
            <p>Get a custom quote for your printing and designing needs</p>
        </div>
    </header>

    <div class="order-container">
        <div class="order-form-container">
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="order.php" method="POST" class="order-form" enctype="multipart/form-data">
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
                                value="<?php 
                                    $prefillSize = '';
                                    $sv = $selected_service ?: ($_POST['service_type'] ?? '');
                                    if (isset($_POST['size'])) { $prefillSize = htmlspecialchars($_POST['size']); }
                                    elseif ($sv && isset($default_sizes[$sv])) { $prefillSize = htmlspecialchars($default_sizes[$sv]); }
                                    echo $prefillSize;
                                ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description & Requirements</label>
                        <textarea name="description" id="description" rows="4" 
                            placeholder="Describe your requirements, colors, text, design preferences, etc."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                    <div class="form-section">
                    <h3>🖼️ Choose a Prebuilt Design (optional)</h3>
                    <p style="color:#5b6b7f;margin:-10px 0 15px;">Pick one of our ready templates or upload your own reference above.</p>
                    <?php 
                        $svcKey = $_POST['service_type'] ?? $selected_service ?? '';
                        $list = $template_options[$svcKey] ?? $template_options['default'];
                    ?>
                    <div class="template-grid">
                        <?php foreach ($list as $idx => $tpl): 
                            $src = htmlspecialchars($tpl['src']);
                            $checked = (isset($_POST['selected_template']) && $_POST['selected_template'] === $src) ? 'checked' : '';
                        ?>
                        <label class="template-option">
                            <input type="radio" name="selected_template" value="<?php echo $src; ?>" <?php echo $checked; ?>>
                            <img src="<?php echo $src; ?>" alt="Template <?php echo $idx+1; ?>">
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <small style="display:block;color:#7f8c8d;margin-top:10px;">Select any template per service or upload your own image.</small>
                </div>
                    <div class="form-group">
                        <label for="reference_image">Reference Image (optional)</label>
                        <input type="file" name="reference_image" id="reference_image" accept="image/*">
                    </div>

                    <div id="price-box" class="form-group" style="background:#f8f9fa;padding:14px;border-radius:8px;border-left:4px solid #667eea;">
                        <strong>Estimated Price:</strong> <span id="est-price">₹ 0.00</span>
                        <div style="color:#666;font-size:0.9rem;margin-top:6px;">Auto-calculated from service and quantity. Urgent adds ₹ <?php echo $urgent_fee; ?>.</div>
                    </div>
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="urgent" id="urgent" value="1" <?php echo (isset($_POST['urgent']) && $_POST['urgent']) ? 'checked' : ''; ?>>
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

    <script>
      (function(){
        const unitRates = <?php echo json_encode($unit_rates); ?>;
        const defaultSizes = <?php echo json_encode($default_sizes); ?>;
        const urgentFee = <?php echo (int)$urgent_fee; ?>;
        const select = document.getElementById('service_type');
        const qty = document.getElementById('quantity');
        const urgent = document.getElementById('urgent');
        const out = document.getElementById('est-price');
        const sizeInput = document.getElementById('size');
        function fmt(n){return '₹ ' + Number(n).toFixed(2);}      
        function applyDefaultSize(){
          const key = select.value || '';
          if (defaultSizes[key] && (!sizeInput.value || sizeInput.value.trim() === '' || sizeInput.dataset.autofilled === 'true')) {
            sizeInput.value = defaultSizes[key];
            sizeInput.dataset.autofilled = 'true';
          }
        }
        function calc(){
          const key = select.value || '';
          const rate = unitRates[key] ? Number(unitRates[key]) : 0;
          const q = Math.max(1, Number(qty.value || 1));
          let total = rate * q;
          if (urgent && urgent.checked) total += urgentFee;
          out.textContent = fmt(total);
        }
        // template selection hook (no pricing)
        function bindTemplateRadios(){
          document.querySelectorAll('input[name="selected_template"]').forEach(function(r){
            r.addEventListener('change', function(){ calc(); });
          });
        }
        bindTemplateRadios();
        select && select.addEventListener('change', function(){ 
          applyDefaultSize(); 
          // When service changes, reload template grid with server-rendered defaults via simple reload
          this.form.submit();
        });
        qty && qty.addEventListener('input', calc);
        urgent && urgent.addEventListener('change', calc);
        sizeInput && sizeInput.addEventListener('input', function(){ this.dataset.autofilled = 'false'; });
        // Toggle reference sections
        var modeUpload = document.getElementById('ref_mode_upload');
        var modeTemplate = document.getElementById('ref_mode_template');
        var secUpload = document.getElementById('ref-upload-section');
        var secTemplate = document.getElementById('ref-template-section');
        function syncRefMode(){
          if (modeTemplate && modeTemplate.checked) {
            if (secTemplate) secTemplate.style.display = 'block';
            if (secUpload) secUpload.style.display = 'none';
          } else {
            if (secTemplate) secTemplate.style.display = 'none';
            if (secUpload) secUpload.style.display = 'block';
          }
        }
        modeUpload && modeUpload.addEventListener('change', syncRefMode);
        modeTemplate && modeTemplate.addEventListener('change', syncRefMode);
        // Initial
        applyDefaultSize();
        calc();
        syncRefMode();
      })();
    </script>
</body>

</html>