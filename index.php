<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pharmalyze</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body>
  <!-- header/nav -->
  <header>
    <nav>
      <div id="menu-toggle" class="fa fa-bars" onclick="toggleMenu()"></div>

      <div id="logo">Pharmalyze</div>

      <div class="nav-links">
        <a class="nav-link" href="#">Home</a>
        <a class="nav-link" href="#services">Services</a>
        <a class="nav-link" href="#about">About Us</a>
        <a class="nav-link" href="#contact">Contact</a>
        <a href="auth/sign_in"><button class="btn signin">Sign In</button></a>
        <a href="auth/sign_up"><button class="btn signup">Sign Up</button></a>
      </div>
    </nav>
  </header>

  <main>
    <section id="hero-section">
      <div class="hero-text">
        <h1 class="headline-primary">Empowering Pharmacy Operations</h1>
        <h4 class="headline-secondary">Manage, Buy, and Sell Medicines Seamlessly</h4>
        <p class="description">
          Pharmalyze is the ultimate platform for pharmacies: track your inventory, get expiry
          alerts, and purchase medicines directly from suppliers with a few clicks. Streamline operations and
          stay ahead in healthcare delivery.
        </p>
        <a href="auth/sign_up"><button class="btn signup">Get Started</button></a>
      </div>
      <div class="hero-image">
        <img src="assets/hero_image.png" width="400" />
      </div>
    </section>
    <section class="stats">
      <div class="stat-box">
        <h2>500+</h2>
        <p>Medicines Managed</p>
      </div>

      <div class="stat-box">
        <h2>100+</h2>
        <p>Pharmacies Connected</p>
      </div>

      <div class="stat-box">
        <h2>50+</h2>
        <p>Verified Suppliers</p>
      </div>

      <div class="stat-box">
        <h2>99%</h2>
        <p>Customer Satisfaction</p>
      </div>
    </section>
  </main>

  <!-- services -->
  <section class="services" id="services">
    <h1>Our Services</h1>
    <p>Everything a modern pharmacy needs in one platform</p>
    <div class="services-container">
      <div class="service-box">
        <h2>Inventory Management</h2>
        <p>
          Monitor medicine stock, batch details, and expiry dates in real-time. Avoid shortages, reduce waste, and
          optimize inventory automatically.
        </p>
      </div>

      <div class="service-box">
        <h2>Buying from Suppliers</h2>
        <p>
          Purchase medicines directly from verified suppliers via our integrated e-commerce marketplace. Track orders,
          compare prices, and restock efficiently.
        </p>
      </div>

      <!-- <div class="service-box">
        <h2>Sales & Billing</h2>
        <p>
          Process sales easily with automatic stock updates, generate invoices, and maintain accurate records for
          compliance.
        </p>
      </div> -->

      <div class="service-box">
        <h2>Expiry Alerts</h2>
        <p>
          Receive notifications for soon-to-expire medicines. Take timely action to minimize losses and ensure safe
          dispensing.
        </p>
      </div>
    </div>
  </section>

  <!-- why choose us section -->
  <section class="features" id="features">
    <h1>Why Pharmalyze?</h1>
    <p>Trusted by pharmacies across Nepal to simplify operations and supply chain management.</p>
    <div class="features-container">
      <!-- <div class="feature-box">
        <i class="fas fa-shield-alt"></i>
        <h2>Secure & Compliant</h2>
        <p>All your inventory and transaction data is encrypted, ensuring privacy and compliance with local regulations.
        </p>
      </div> -->
      <div class="feature-box">
        <i class="fas fa-truck"></i>
        <h2>Direct Supplier Access</h2>
        <p>Order medicines directly from verified suppliers via the e-commerce platform with transparent pricing and
          delivery updates.</p>
      </div>
      <div class="feature-box">
        <i class="fas fa-chart-line"></i>
        <h2>Smart Analytics</h2>
        <p>Generate reports for inventory, sales trends, and procurement to make data-driven decisions.</p>
      </div>
      <div class="feature-box">
        <i class="fas fa-bolt"></i>
        <h2>Fast & Efficient</h2>
        <p>Save time with automated stock updates, quick supplier ordering, and easy-to-use interface.</p>
      </div>
    </div>
  </section>

  <section class="testimonials" id="testimonials">
    <h1>What Our Users Say</h1>
    <p>Pharmacies and clinics trust Pharmalyze for seamless operations.</p>
    <div class="testimonial-container">
      <div class="testimonial-box">
        <p>"Pharmalyze transformed our pharmacy! Inventory management is effortless and ordering from suppliers is just
          a click away."</p>
        <h3>– Ramesh, Biratnagar Pharmacy</h3>
      </div>
      <div class="testimonial-box">
        <p>"Automatic expiry alerts and supplier marketplace have saved us time and reduced losses."</p>
        <h3>– Sita, Health Clinic</h3>
      </div>
      <div class="testimonial-box">
        <p>"Easy, fast, and reliable. Pharmalyze is perfect for modern pharmacies."</p>
        <h3>– Anil, City Pharmacy</h3>
      </div>
    </div>
  </section>

  <!-- about us  -->
  <!-- <section class="about_us" id="about">
    <h1>About Us</h1>
    <p>
      Pharmalyze is a technology-first company focused on delivering reliable,
      scalable, and secure healthcare solutions. Our mission is to innovate,
      inspire, and improve lives through data-driven tools.
    </p>
  </section> -->


  <section class="cta" id="cta">
    <div class="cta-container">
      <h1>Ready to Upgrade Your Pharmacy?</h1>
      <p>Start using Pharmalyze today and manage your inventory and supplier orders effortlessly.</p>
      <a href="auth/sign_up"><button class="btn signup">Get Started Now</button></a>
    </div>
  </section>

  <!-- footer -->

  <footer class="footer">
    <div class="footer-container">
      <div class="footer-about">
        <h3>About Us</h3>
        <p>
          Pharmalyze is a technology-first company focused on delivering
          reliable, scalable, and secure healthcare solutions. Our mission is
          to innovate, inspire, and improve lives through data-driven tools.
        </p>
      </div>

      <div class="footer-links">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#">Services</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>

      <div class="footer-contact" id="contact">
        <h3>Contact Us</h3>
        <p>Email: info@pharmalyze.com</p>
        <p>Phone: +977-98XXXXXXX</p>
        <p>Location: Biratnagar, Nepal</p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2026 Pharmalyze. All rights reserved.</p>
    </div>
  </footer>

  <!--javascript code-->
  <script>
    function toggleMenu() {
      const navLinks = document.querySelector(".nav-links");
      navLinks.classList.toggle("active");
    }

    // Close menu when a nav link is clicked
    document.querySelectorAll(".nav-links a").forEach((link) => {
      link.addEventListener("click", () => {
        const navLinks = document.querySelector(".nav-links");
        if (navLinks.classList.contains("active")) {
          navLinks.classList.remove("active");
        }
      });
    });

    // animation script

    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {

        if (entry.isIntersecting) {
          entry.target.classList.add("show");

          // stop observing after animation runs
          observer.unobserve(entry.target);
        }

      });
    }, { threshold: 0.2 }); // start animation when 20% is visible


    // observe hero text
    const heroText = document.querySelector(".hero-text");
    observer.observe(heroText);


    // observe services section
    const servicesSection = document.querySelector(".services");
    observer.observe(servicesSection);


    // observe each service card
    const serviceCards = document.querySelectorAll(".service-box");
    serviceCards.forEach(card => {
      observer.observe(card);
    });

    // observe feature cards
    const featureCards = document.querySelectorAll(".feature-box");

    featureCards.forEach(card => {
      observer.observe(card);
    });

    // observe testimonial cards
    const testimonialCards = document.querySelectorAll(".testimonial-box");

    testimonialCards.forEach(card => {
      observer.observe(card);
    });
  </script>
</body>

</html>