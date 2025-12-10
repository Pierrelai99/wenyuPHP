<?php
session_start();

// Page variables
$page_title = "About Us";
$page_description = "Learn about FishyWishy Seafood Store - your trusted source for fresh, quality seafood";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'about.php', 'title' => 'About Us']
];

// Include header
include '../includes/header.php';
?>

<!-- About Hero Section -->
<section class="about-hero-section">
    <div class="container">
        <div class="about-hero-content">
            <h1>About FishyWishy Seafood Store</h1>
            <p>Delivering ocean-fresh seafood to Malaysian families since 2010</p>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="our-story-section">
    <div class="container">
        <div class="story-layout">
            <div class="story-content">
                <h2>Our Story</h2>
                <p>Founded in 2010 by a group of passionate Malaysian fishermen and seafood experts in Pelabuhan Klang, FishyWishy Seafood Store began with a simple mission: to provide high-quality, fresh, and sustainable seafood that brings the taste of the ocean directly to your table.</p>
                
                <p>What started as a small local fish market in Pelabuhan Klang has grown into one of Malaysia's most trusted online destinations for premium seafood. We've maintained our commitment to freshness and quality while expanding our selection to include a wide variety of fish, shellfish, and seafood products from Malaysia's finest waters and trusted international sources.</p>
                
                <p>Today, we serve families across Malaysia and Southeast Asia, helping home cooks and seafood lovers access restaurant-quality seafood for their culinary needs, celebrating our rich coastal heritage and diverse seafood culture.</p>
            </div>
            <div class="story-image">
                <img src="assets/images/about-story.jpg" alt="FishyWishy Seafood Store History">
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
                <p>To deliver the freshest, highest-quality seafood from ocean to table, supporting sustainable fishing practices while bringing families together through delicious, healthy meals.</p>
            </div>
            
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Our Vision</h3>
                <p>To be Southeast Asia's leading destination for premium seafood, recognized for our commitment to freshness, sustainability, and customer satisfaction while celebrating our diverse coastal culinary heritage.</p>
            </div>
            
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Our Values</h3>
                <ul>
                    <li>Freshness and Quality First</li>
                    <li>Customer Satisfaction</li>
                    <li>Sustainable Sourcing</li>
                    <li>Malaysian Coastal Values</li>
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
            <h2>Why Choose FishyWishy Seafood Store?</h2>
            <p>We're committed to providing the best seafood shopping experience for Malaysian families</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Freshness Guaranteed</h3>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Sustainable Sourcing</h3>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3>Fast & Cold Chain Delivery</h3>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Expert Support</h3>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h3>Easy Returns</h3>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <h3>Best Prices</h3>
            </div>
        </div>
    </div>
</section>

<!-- Our Team Section -->
<section class="our-team-section">
    <div class="container">
        <div class="section-header">
            <h2>Meet Our Malaysian Team</h2>
            <p>The passionate people behind FishyWishy Seafood Store</p>
        </div>
        
        <div class="team-grid">
            <div class="team-member">
                <div class="member-image">
                    <img src="assets/images/team-ceo.jpg" alt="Wen Yu - CEO">
                </div>
                <div class="member-info">
                    <h3>Wen Yu</h3>
                    <span class="position">CEO & Founder</span>
                    <p>Founded FishyWishy Seafood Store with a vision to make fresh, quality seafood accessible to all Malaysian families.</p>
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
                <div class="stat-number">200+</div>
                <div class="stat-label">Seafood Varieties Available</div>
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
            <p>Real stories from families who love our seafood</p>
        </div>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"FishyWishy Seafood Store has been our go-to for fresh fish and prawns. The quality is always excellent and the delivery is outstanding! Always arrives fresh to Selangor!"</p>
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
                    <p>"As a restaurant owner in Johor, I appreciate the consistent quality and freshness of their seafood selection. My customers love the dishes we prepare with seafood from FishyWishy, and they deliver to our restaurant quickly."</p>
                </div>
                <div class="testimonial-author">
                    <img src="assets/images/customer-2.jpg" alt="Robert Davis">
                    <div>
                        <h4>Encik Rahman</h4>
                        <span>Restaurant Owner, JB</span>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Fast cold chain delivery across Malaysia, great prices, and amazing selection. We've been ordering here for years and never been disappointed! Perfect for Hari Raya feasts!"</p>
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
            <p>Our team is here to help you find the perfect seafood for your family</p>
            <div class="cta-buttons">
                <a href="contact.php" class="btn btn-primary">Contact Us</a>
                <a href="faq.php" class="btn btn-outline">View FAQ</a>
            </div>
        </div>
    </div>
</section>
<?php
// Include footer
include '../includes/footer.php';
?> 