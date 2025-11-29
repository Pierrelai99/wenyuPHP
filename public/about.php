<?php
session_start();

// Page variables
$page_title = "About Us";
$page_description = "Learn about ToyLand Store - your trusted source for quality toys and games";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'about.php', 'title' => 'About Us']
];

// Include header
include 'includes/header.php';
?>

<!-- About Hero Section -->
<section class="about-hero-section">
    <div class="container">
        <div class="about-hero-content">
            <h1>About ToyLand Store</h1>
            <p>Bringing joy and imagination to Malaysian families since 2010</p>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="our-story-section">
    <div class="container">
        <div class="story-layout">
            <div class="story-content">
                <h2>Our Story</h2>
                <p>Founded in 2010 by a group of passionate Malaysian parents and educators in Kuala Lumpur, ToyLand Store began with a simple mission: to provide high-quality, educational, and fun toys that help children learn and grow while having a great time.</p>
                
                <p>What started as a small local toy store in Pavilion Kuala Lumpur has grown into one of Malaysia's most trusted online destinations for toys and games. We've maintained our commitment to quality and safety while expanding our selection to include thousands of products from the world's most respected toy manufacturers.</p>
                
                <p>Today, we serve families across Malaysia and Southeast Asia, helping parents find the perfect toys for their children's development and entertainment needs, celebrating our rich multicultural heritage through play.</p>
            </div>
            <div class="story-image">
                <img src="assets/images/about-story.jpg" alt="ToyLand Store History">
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="mission-values-section">
    <div class="container">
        <div class="mission-values-grid">
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Our Mission</h3>
                <p>To inspire creativity, learning, and joy through carefully curated toys and games that support children's development and bring families together.</p>
            </div>
            
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Our Vision</h3>
                <p>To be Southeast Asia's leading destination for quality toys and games, recognized for our commitment to safety, education, and customer satisfaction while celebrating our diverse cultural heritage.</p>
            </div>
            
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Our Values</h3>
                <ul>
                    <li>Quality and Safety First</li>
                    <li>Customer Satisfaction</li>
                    <li>Educational Excellence</li>
                    <li>Malaysian Family Values</li>
                    <li>Community Support</li>
                    <li>Cultural Diversity & Inclusion</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-us-section">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose ToyLand Store?</h2>
            <p>We're committed to providing the best shopping experience for Malaysian families</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Safety Guaranteed</h3>
                <p>All our toys meet or exceed international safety standards. We carefully vet every product before adding it to our collection.</p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Educational Focus</h3>
                <p>We prioritize toys that promote learning, creativity, and skill development. Every product is selected with educational value in mind.</p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3>Fast & Reliable Shipping</h3>
                <p>Quick delivery across Malaysia with careful packaging to ensure your toys arrive in perfect condition. Same-day delivery available in Klang Valley.</p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Expert Support</h3>
                <p>Our toy experts are here to help you find the perfect products for your child's age and interests.</p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h3>Easy Returns</h3>
                <p>Not satisfied? We offer hassle-free returns and exchanges to ensure you're completely happy with your purchase.</p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <h3>Best Prices</h3>
                <p>We work directly with manufacturers to offer competitive prices without compromising on quality.</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Team Section -->
<section class="our-team-section">
    <div class="container">
        <div class="section-header">
            <h2>Meet Our Malaysian Team</h2>
            <p>The passionate people behind ToyLand Store</p>
        </div>
        
        <div class="team-grid">
            <div class="team-member">
                <div class="member-image">
                    <img src="assets/images/team-ceo.jpg" alt="Stepahnie - CEO">
                </div>
                <div class="member-info">
                    <h3>Stephanie</h3>
                    <span class="position">CEO & Founder</span>
                    <p>Founded ToyLand Store with a vision to make quality educational toys accessible to all Malaysian families.</p>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-image">
                    <img src="assets/images/team-cto.jpg" alt="Salman - CTO">
                </div>
                <div class="member-info">
                    <h3>Salman</h3>
                    <span class="position">Chief Technology Officer</span>
                    <p>He leads our technology initiatives, ensuring our website provides the best shopping experience with secure, fast, and user-friendly features for Malaysian customers.</p>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-image">
                    <img src="assets/images/team-curator.jpg" alt="Wen Yu - Product Curator">
                </div>
                <div class="member-info">
                    <h3>Wen Yu</h3>
                    <span class="position">Product Curator</span>
                    <p>She has a keen eye for quality toys and works tirelessly to find the best products that combine fun, education, and safety, with special focus on multicultural learning.</p>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-image">
                    <img src="assets/images/team-support.jpg" alt="Aiman - Customer Support">
                </div>
                <div class="member-info">
                    <h3>Aiman</h3>
                    <span class="position">Customer Support Manager</span>
                    <p>Aiman and his multilingual team ensure every customer receives personalized attention and expert advice to find the perfect toys for their needs, in English, Bahasa Malaysia, and Mandarin.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">75,000+</div>
                <div class="stat-label">Happy Malaysian Families</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">15,000+</div>
                <div class="stat-label">Products Available</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">14</div>
                <div class="stat-label">Years of Experience</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-number">4.9/5</div>
                <div class="stat-label">Customer Rating</div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2>What Our Malaysian Customers Say</h2>
            <p>Real stories from families who love our toys</p>
        </div>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"ToyLand Store has been our go-to for birthday gifts and educational toys. The quality is always excellent and the customer service is outstanding! Fast delivery to Selangor too!"</p>
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/customer-1.jpg" alt="Lisa Chen">
                    <div>
                        <h4>Lisa Wong</h4>
                        <span>Verified Customer, KL</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"As a teacher in Johor, I appreciate the educational focus of their toy selection. My students love the learning games we get from ToyLand Store, and they deliver to our school quickly."</p>
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/customer-2.jpg" alt="Robert Davis">
                    <div>
                        <h4>Encik Rahman</h4>
                        <span>Primary School Teacher, JB</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Fast shipping across Malaysia, great prices, and amazing selection. We've been shopping here for years and never been disappointed! Perfect for Hari Raya gifts!"</p>
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/customer-3.jpg" alt="Maria Garcia">
                    <div>
                        <h4>Siti Aishah</h4>
                        <span>Verified Customer, Penang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA Section -->
<section class="contact-cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Have Questions?</h2>
            <p>Our team is here to help you find the perfect toys for your family</p>
            <div class="cta-buttons">
                <a href="contact.php" class="btn btn-primary">Contact Us</a>
                <a href="faq.php" class="btn btn-outline">View FAQ</a>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';
?> 