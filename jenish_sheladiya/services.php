<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Our Services - Shyam Enterprise</title>
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/services_css.css" />
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">Shyam Enterprise</a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="services.php" class="nav-link active">Services</a></li>
                <li class="nav-item"><a href="order.php" class="nav-link">Order Now</a></li>
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

    <div class="services-container">
        <div class="hero-section">
            <h1>All Types Of Printing & Designing Solutions</h1>
            <p>Perfect Solution For Your Advertisement & Branding</p>
        </div>

        <div class="services-grid">
            <!-- Flex Board Banner -->
            <div class="service-card">
                <div class="service-image">📋</div>
                <h3>Flex Board Banner</h3>
                <p>High-quality outdoor advertising banners for maximum visibility and impact.</p>
                <div class="service-features">
                    <span>✓ Weather Resistant</span>
                    <span>✓ Custom Sizes</span>
                    <span>✓ Vibrant Colors</span>
                </div>
                <a href="order.php?service=flexboard" class="service-btn">Order Now</a>
            </div>

            <!-- Vinyl Sticker Printing -->
            <div class="service-card">
                <div class="service-image">🏷️</div>
                <h3>Vinyl Sticker Printing</h3>
                <p>Durable and attractive vinyl stickers for branding and promotional purposes.</p>
                <div class="service-features">
                    <span>✓ Waterproof</span>
                    <span>✓ Custom Designs</span>
                    <span>✓ Multiple Finishes</span>
                </div>
                <a href="order.php?service=vinyl" class="service-btn">Order Now</a>
            </div>

            <!-- One-Way Print -->
            <div class="service-card">
                <div class="service-image">👁️</div>
                <h3>One-Way Vision Print</h3>
                <p>Perfect for window graphics that provide privacy while maintaining visibility.</p>
                <div class="service-features">
                    <span>✓ Privacy Protection</span>
                    <span>✓ UV Resistant</span>
                    <span>✓ Easy Installation</span>
                </div>
                <a href="order.php?service=oneway" class="service-btn">Order Now</a>
            </div>

            <!-- Reflective Vinyl Print -->
            <div class="service-card">
                <div class="service-image">✨</div>
                <h3>Reflective Vinyl Print</h3>
                <p>Safety-focused reflective materials for high-visibility applications.</p>
                <div class="service-features">
                    <span>✓ High Visibility</span>
                    <span>✓ Safety Compliant</span>
                    <span>✓ Long Lasting</span>
                </div>
                <a href="order.php?service=reflective" class="service-btn">Order Now</a>
            </div>

            <!-- Eco HD Print -->
            <div class="service-card">
                <div class="service-image">🌿</div>
                <h3>Eco HD Print</h3>
                <p>Environmentally friendly high-definition printing solutions.</p>
                <div class="service-features">
                    <span>✓ Eco-Friendly Inks</span>
                    <span>✓ HD Quality</span>
                    <span>✓ Sustainable</span>
                </div>
                <a href="order.php?service=ecohd" class="service-btn">Order Now</a>
            </div>

            <!-- Lighting Board -->
            <div class="service-card">
                <div class="service-image">💡</div>
                <h3>Lighting Board</h3>
                <p>Illuminated signage solutions for 24/7 visibility and impact.</p>
                <div class="service-features">
                    <span>✓ LED Technology</span>
                    <span>✓ Energy Efficient</span>
                    <span>✓ Custom Lighting</span>
                </div>
                <a href="order.php?service=lighting" class="service-btn">Order Now</a>
            </div>

            <!-- Roll-Up Standee -->
            <div class="service-card">
                <div class="service-image">📊</div>
                <h3>Roll-Up Standee</h3>
                <p>Portable and professional display stands for exhibitions and events.</p>
                <div class="service-features">
                    <span>✓ Portable Design</span>
                    <span>✓ Easy Setup</span>
                    <span>✓ Professional Look</span>
                </div>
                <a href="order.php?service=rollup" class="service-btn">Order Now</a>
            </div>

            <!-- Canopy -->
            <div class="service-card">
                <div class="service-image">⛺</div>
                <h3>Canopy</h3>
                <p>Branded canopies for outdoor events, exhibitions, and promotional activities.</p>
                <div class="service-features">
                    <span>✓ Weather Resistant</span>
                    <span>✓ Custom Branding</span>
                    <span>✓ Easy Assembly</span>
                </div>
                <a href="order.php?service=canopy" class="service-btn">Order Now</a>
            </div>

            <!-- L.E.D Board -->
            <div class="service-card">
                <div class="service-image">🔆</div>
                <h3>L.E.D Board</h3>
                <p>Dynamic LED displays for modern advertising and information display.</p>
                <div class="service-features">
                    <span>✓ Digital Display</span>
                    <span>✓ Programmable</span>
                    <span>✓ High Brightness</span>
                </div>
                <a href="order.php?service=ledboard" class="service-btn">Order Now</a>
            </div>

            <!-- Industrial Safety Sign Board -->
            <div class="service-card">
                <div class="service-image">⚠️</div>
                <h3>Industrial Safety Sign Board</h3>
                <p>Compliant safety signage for industrial and commercial environments.</p>
                <div class="service-features">
                    <span>✓ Safety Compliant</span>
                    <span>✓ Durable Materials</span>
                    <span>✓ Clear Visibility</span>
                </div>
                <a href="order.php?service=safety" class="service-btn">Order Now</a>
            </div>

            <!-- A.C.P Board -->
            <div class="service-card">
                <div class="service-image">🏢</div>
                <h3>A.C.P Board</h3>
                <p>Aluminum Composite Panel boards for premium outdoor signage.</p>
                <div class="service-features">
                    <span>✓ Premium Material</span>
                    <span>✓ Weather Resistant</span>
                    <span>✓ Professional Finish</span>
                </div>
                <a href="order.php?service=acp" class="service-btn">Order Now</a>
            </div>

            <!-- Foam Board -->
            <div class="service-card">
                <div class="service-image">📌</div>
                <h3>Foam Board</h3>
                <p>Lightweight and versatile foam boards for indoor displays and presentations.</p>
                <div class="service-features">
                    <span>✓ Lightweight</span>
                    <span>✓ Easy to Mount</span>
                    <span>✓ Clean Finish</span>
                </div>
                <a href="order.php?service=foam" class="service-btn">Order Now</a>
            </div>
        </div>

        <!-- Additional Services Section -->
        <div class="additional-services">
            <h2>Print & Stationery Services</h2>
            <div class="stationery-grid">
                <a class="stationery-item" href="order.php?service=visiting_card">
                    <span class="item-icon">📇</span>
                    <span>Visiting Cards</span>
                </a>
                <a class="stationery-item" href="order.php?service=letterhead">
                    <span class="item-icon">📄</span>
                    <span>Letter Head</span>
                </a>
                <a class="stationery-item" href="order.php?service=billbook">
                    <span class="item-icon">📚</span>
                    <span>Bill Book</span>
                </a>
                <a class="stationery-item" href="order.php?service=envelope">
                    <span class="item-icon">✉️</span>
                    <span>Envelope</span>
                </a>
                <a class="stationery-item" href="order.php?service=brochure">
                    <span class="item-icon">📖</span>
                    <span>Brochure</span>
                </a>
                <a class="stationery-item" href="order.php?service=pamphlet">
                    <span class="item-icon">📑</span>
                    <span>Pamphlet</span>
                </a>
                <a class="stationery-item" href="order.php?service=idcard">
                    <span class="item-icon">🆔</span>
                    <span>ID Card</span>
                </a>
                <a class="stationery-item" href="order.php?service=stickers">
                    <span class="item-icon">🏷️</span>
                    <span>Stickers</span>
                </a>
                <a class="stationery-item" href="order.php?service=invitation">
                    <span class="item-icon">💌</span>
                    <span>Invitation Card</span>
                </a>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="contact-cta">
            <h2>Need a Custom Solution?</h2>
            <p>Contact us for personalized printing and designing solutions tailored to your business needs.</p>
            <div class="cta-buttons">
                <a href="contact.php" class="cta-btn primary">Contact Us</a>
                <a href="order.php" class="cta-btn secondary">Get Quote</a>
            </div>
        </div>
    </div>
</body>

</html>