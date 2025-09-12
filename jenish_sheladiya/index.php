<?php
session_start();
require 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shyam Enterprise - All Types Of Printing & Designing Solutions</title>
  <meta name="description" content="Shyam Enterprise provides all types of printing and designing solutions in Ankleshwar. Perfect solution for your advertisement and branding needs.">
  <link rel="stylesheet" href="css/base.css" />
  <link rel="stylesheet" href="css/homepage.css" />
</head>

<body>

  <nav class="navbar">
    <div class="nav-container">
      <a href="index.php" class="nav-logo">Shyam Enterprise</a>
      <ul class="nav-menu">
        <li class="nav-item"><a href="index.php" class="nav-link active">Home</a></li>
        <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
        <li class="nav-item"><a href="order.php" class="nav-link">Order Now</a></li>
        <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
        <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <?php 
          // Check if user is admin (with error handling)
          $user_id = $_SESSION['user_id'];
          $user_role = 'user'; // default
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
          } catch (Exception $e) {
              // Role column doesn't exist yet, use default
              $user_role = 'user';
          }
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

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <div class="hero-text">
        <h1>All Types Of Printing & Designing Solutions</h1>
        <p class="hero-subtitle">Perfect Solution For Your Advertisement & Branding</p>
        <div class="hero-buttons">
          <a href="services.php" class="btn btn-primary">Explore Services</a>
          <a href="order.php" class="btn btn-secondary">Get Quote</a>
        </div>
      </div>
      <div class="hero-image">
        <div class="logo-container">
          <img src="logo.jpg" alt="Shyam Enterprise Logo" class="logo-image" />
        </div>
      </div>
    </div>
  </section>

  <!-- Services Preview -->
  <section class="services-preview">
    <div class="container">
      <h2>Our Popular Services</h2>
      <div class="services-grid">
        <div class="service-card" style="cursor:pointer" onclick="location.href='order.php?service=flexboard'">
          <div class="service-icon">📋</div>
          <h3>Flex Board Banner</h3>
          <p>Weather resistant outdoor advertising</p>
        </div>
        <div class="service-card" style="cursor:pointer" onclick="location.href='order.php?service=vinyl'">
          <div class="service-icon">🏷️</div>
          <h3>Vinyl Sticker Printing</h3>
          <p>Durable custom vinyl stickers</p>
        </div>
        <div class="service-card" style="cursor:pointer" onclick="location.href='order.php?service=oneway'">
          <div class="service-icon">👁️</div>
          <h3>One-Way Print</h3>
          <p>Privacy window graphics</p>
        </div>
        <div class="service-card" style="cursor:pointer" onclick="location.href='order.php?service=reflective'">
          <div class="service-icon">✨</div>
          <h3>Reflective Vinyl Print</h3>
          <p>High-visibility safety materials</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features-section">
    <div class="container">
      <div class="features-grid">
        <div class="feature">
          <div class="feature-icon">🎨</div>
          <h3>Creative Design</h3>
          <p>Professional design services tailored to your brand identity and marketing needs.</p>
        </div>
        <div class="feature">
          <div class="feature-icon">⚡</div>
          <h3>Fast Delivery</h3>
          <p>Quick turnaround times to meet your urgent printing and advertising requirements.</p>
        </div>
        <div class="feature">
          <div class="feature-icon">💎</div>
          <h3>Premium Quality</h3>
          <p>High-quality materials and advanced printing technology for lasting results.</p>
        </div>
        <div class="feature">
          <div class="feature-icon">💰</div>
          <h3>Competitive Pricing</h3>
          <p>Affordable pricing without compromising on quality for all your printing needs.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Products Showcase -->
  <section class="products-showcase">
    <div class="container">
      <h2>Our Product Range</h2>
      <div class="products-grid">
        <div class="product-category">
          <h3>🏢 Outdoor Advertising</h3>
          <ul>
            <li>Flex Board Banner</li>
            <li>Vinyl Sticker Printing</li>
            <li>One-Way Vision Print</li>
            <li>Reflective Vinyl Print</li>
            <li>A.C.P Board</li>
            <li>L.E.D Board</li>
          </ul>
        </div>
        <div class="product-category">
          <h3>🖨️ Digital Printing</h3>
          <ul>
            <li>Eco HD Print</li>
            <li>Roll-Up Standee</li>
            <li>Foam Board</li>
            <li>Canopy</li>
            <li>Lighting Board</li>
          </ul>
        </div>
        <div class="product-category">
          <h3>📋 Business Stationery</h3>
          <ul>
            <li>Visiting Cards</li>
            <li>Letter Head</li>
            <li>Bill Book</li>
            <li>Envelope</li>
            <li>Brochure</li>
            <li>ID Cards</li>
          </ul>
        </div>
        <div class="product-category">
          <h3>🛡️ Safety & Industrial</h3>
          <ul>
            <li>Industrial Safety Sign Board</li>
            <li>Pamphlet</li>
            <li>Stickers</li>
            <li>Invitation Cards</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="why-choose-us">
    <div class="container">
      <div class="why-content">
        <div class="why-text">
          <h2>Why Choose Shyam Enterprise?</h2>
          <div class="why-points">
            <div class="why-point">
              <span class="point-icon">✅</span>
              <div>
                <h4>10+ Years Experience</h4>
                <p>Trusted by businesses across Ankleshwar and Gujarat</p>
              </div>
            </div>
            <div class="why-point">
              <span class="point-icon">✅</span>
              <div>
                <h4>Latest Technology</h4>
                <p>Modern printing equipment for superior quality output</p>
              </div>
            </div>
            <div class="why-point">
              <span class="point-icon">✅</span>
              <div>
                <h4>Custom Solutions</h4>
                <p>Tailored printing and design solutions for every business need</p>
              </div>
            </div>
            <div class="why-point">
              <span class="point-icon">✅</span>
              <div>
                <h4>On-Time Delivery</h4>
                <p>Committed to meeting deadlines and exceeding expectations</p>
              </div>
            </div>
          </div>
        </div>
        <div class="why-image">
          <div class="stats-card">
            <div class="stat">
              <h3>1000+</h3>
              <p>Happy Customers</p>
            </div>
            <div class="stat">
              <h3>50+</h3>
              <p>Service Types</p>
            </div>
            <div class="stat">
              <h3>24/7</h3>
              <p>Support</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact CTA -->
  <section class="contact-cta">
    <div class="container">
      <div class="cta-content">
        <h2>Ready to Get Started?</h2>
        <p>Contact us today for a free consultation and quote for your printing and branding needs.</p>
        <div class="contact-info-grid">
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
        </div>
        <div class="cta-buttons">
          <a href="contact.php" class="btn btn-white">Contact Us</a>
          <a href="order.php" class="btn btn-outline">Get Quote</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h3>Shyam Enterprise</h3>
          <p>Your trusted partner for all printing and designing solutions in Ankleshwar, Gujarat.</p>
        </div>
        <div class="footer-section">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Services</h4>
          <ul>
            <li><a href="services.php">Flex Board Banner</a></li>
            <li><a href="services.php">Vinyl Sticker Printing</a></li>
            <li><a href="services.php">One-Way Vision Print</a></li>
            <li><a href="services.php">Reflective Vinyl Print</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Contact Info</h4>
          <ul>
            <li>📱 +91 96013 40892</li>
            <li>📧 shyamenterprise888@gmail.com</li>
            <li>📍 Ankleshwar, Gujarat</li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

</body>

</html>