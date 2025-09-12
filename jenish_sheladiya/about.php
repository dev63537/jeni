<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>About Us - Shyam Enterprise</title>
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/services.css" />
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
                <li class="nav-item"><a href="about.php" class="nav-link active">About Us</a></li>
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

    <header class="page-hero">
        <div class="container">
            <h1>About Shyam Enterprise</h1>
            <p>Your Trusted Partner in Printing & Designing Solutions</p>
        </div>
    </header>

    <div class="services-container">
        <!-- Company Story -->
        <div class="service-category">
            <h3>Our Story</h3>
            <div class="service-card">
                <p>Shyam Enterprise has been serving the Ankleshwar and Gujarat region for over 10 years, providing comprehensive printing and designing solutions to businesses of all sizes. We started as a small printing shop with a vision to deliver quality and innovation in every project.</p>
                
                <p>Today, we have grown into a full-service printing and design company, equipped with modern technology and a team of skilled professionals who are passionate about bringing your ideas to life.</p>
                
                <p>Our commitment to excellence, customer satisfaction, and timely delivery has made us the preferred choice for businesses looking for reliable printing and branding solutions.</p>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="services-grid">
            <div class="service-card">
                <div class="service-image">🎯</div>
                <h3>Our Mission</h3>
                <p>Our mission is to provide exceptional service and to follow through on our promises. We will strive to deliver individualised solutions to all our client's printing needs and add value to our clients' businesses. The Print Group's staff are focused on providing great service and forming lasting relationships with our clients. We will be judged not only by our words but by our actions.
                </p>
            </div>

            <div class="service-card">
                <div class="service-image">👁️</div>
                <h3>Our Vision</h3>
                <p>The Print Group endeavours to become recognised as the benchmark of outstanding service, not just in the printing industry but across all industries. We pride ourselves on bringing results that count in everything that we set out to do and we are constantly striving for successful outcomes in all areas of our business.

</p>
            </div>

            <div class="service-card">
                <div class="service-image">💎</div>
                <h3>Our Values</h3>
                <p>Our team is our strength and a vital ingredient to everything that we achieve. We value expertise, skill and knowledge in each person's chosen area. Passion, enthusiasm and ambition, personal integrity, honesty, sincerity and above all commitment is what we look for in our people. We strive to provide an environment that attracts highly qualified employees and offers the satisfaction of working in a challenging and successful position.</p>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="service-category">
            <h3>Why Choose Shyam Enterprise?</h3>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-image">🏆</div>
                    <h3>10+ Years Experience</h3>
                    <p>Over a decade of experience in the printing and design industry, serving hundreds of satisfied customers across Gujarat.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">⚡</div>
                    <h3>Fast Turnaround</h3>
                    <p>Quick delivery times without compromising on quality. We understand the importance of meeting your deadlines.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">🎨</div>
                    <h3>Creative Design Team</h3>
                    <p>Our talented designers work closely with you to create unique and effective designs that represent your brand perfectly.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">💰</div>
                    <h3>Competitive Pricing</h3>
                    <p>Affordable rates that provide excellent value for money. We believe quality printing should be accessible to all businesses.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">🔧</div>
                    <h3>Modern Equipment</h3>
                    <p>State-of-the-art printing machines and software to ensure the highest quality output for all your projects.</p>
                </div>

                <div class="service-card">
                    <div class="service-image">🤝</div>
                    <h3>Personalized Service</h3>
                    <p>We treat every project as unique and provide personalized attention to ensure your specific requirements are met.</p>
                </div>
            </div>
        </div>

        <!-- Our Team -->
        <div class="service-category">
            <h3>Our Team</h3>
            <div class="service-card">
                <p>Our team consists of experienced professionals who are passionate about printing and design. From skilled designers who bring creativity to your projects, to technical experts who ensure perfect execution, our team works together to deliver exceptional results.</p>
                
                <p>We continuously invest in training and development to stay updated with the latest trends and technologies in the printing industry. This commitment to excellence is reflected in every project we undertake.</p>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="contact-cta">
            <h2>Ready to Work With Us?</h2>
            <p>Let's discuss your printing and design needs. Contact us today for a free consultation and quote.</p>
            <div class="cta-buttons">
                <a href="contact.php" class="cta-btn primary">Contact Us</a>
                <a href="order.php" class="cta-btn secondary">Get Quote</a>
            </div>
        </div>
    </div>

</body>

</html>
