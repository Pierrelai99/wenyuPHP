<?php
session_start();

// Page variables
$page_title = "Contact Us";
$page_description = "Get in touch with ToyLand Store - we're here to help with all your toy shopping needs";
$show_breadcrumb = true;
$breadcrumb_items = [
    ['url' => 'contact.php', 'title' => 'Contact Us']
];

// Include header
include 'includes/header.php';
?>

<!-- Contact Hero Section -->
<section class="contact-hero-section">
    <div class="container">
        <div class="contact-hero-content">
            <h1>Contact Us</h1>
            <p>We're here to help with all your toy shopping needs across Malaysia</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-layout">
            <!-- Contact Form -->
            <div class="contact-form-container">
                <div class="form-header">
                    <h2>Send Us a Message</h2>
                    <p>Have a question or need assistance? We'd love to hear from you!</p>
                </div>
                
                <form class="contact-form" action="contact-submit.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="product">Product Information</option>
                            <option value="order">Order Status</option>
                            <option value="returns">Returns & Exchanges</option>
                            <option value="shipping">Shipping Information</option>
                            <option value="technical">Technical Support</option>
                            <option value="feedback">Feedback</option>
                            <option value="partnership">Partnership Opportunities</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="order_number">Order Number (if applicable)</label>
                        <input type="text" id="order_number" name="order_number" placeholder="e.g., ORD-12345">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Please provide details about your inquiry..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="newsletter" id="newsletter">
                            <span class="checkmark"></span>
                            Subscribe to our newsletter for updates and special offers
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
            
            <!-- Contact Information -->
            <div class="contact-info">
                <div class="info-header">
                    <h2>Get in Touch</h2>
                    <p>Multiple ways to reach our friendly team</p>
                </div>
                
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="method-content">
                            <h3>Phone Support</h3>
                            <p><strong>Main:</strong> +60 3-2123 4567</p>
                            <p><strong>Toll Free:</strong> 1-300-TOYLAND</p>
                            <p><strong>Hours:</strong> Mon-Fri 9AM-6PM MYT</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="method-content">
                            <h3>Email Support</h3>
                            <p><strong>General:</strong> info@toylandstore.com.my</p>
                            <p><strong>Support:</strong> support@toylandstore.com.my</p>
                            <p><strong>Response:</strong> Within 24 hours</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="method-content">
                            <h3>Live Chat</h3>
                            <p>Available during business hours</p>
                            <p>Click the chat icon in the bottom right</p>
                            <button class="btn btn-outline btn-small">Start Chat</button>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="method-content">
                            <h3>Visit Our Store</h3>
                            <p>Level 3, Pavilion Kuala Lumpur<br>
                            168, Jalan Bukit Bintang<br>
                            55100 Kuala Lumpur, Malaysia</p>
                            <p><strong>Hours:</strong> Daily 10AM-10PM</p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="social-contact">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-youtube"></i>
                            <span>YouTube</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="section-header">
            <h2>Frequently Asked Questions</h2>
            <p>Quick answers to common questions</p>
        </div>
        
        <div class="faq-grid">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>What are your shipping options?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We offer several shipping options across Malaysia: Standard (2-3 business days), Express (1-2 business days), and Same Day Delivery (Klang Valley only). Free shipping is available on orders over RM150.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>How do I return an item?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>You can return items within 30 days of delivery. Simply log into your account, go to your order history, and initiate a return. We'll provide a prepaid shipping label.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Are your toys safe for children?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Absolutely! All our toys meet or exceed international safety standards. We carefully vet every product and only carry items from reputable manufacturers.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Do you ship internationally?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Currently, we ship throughout Malaysia and to Singapore, Brunei, and Thailand. We're expanding our international shipping to more Southeast Asian countries soon. Shipping rates and delivery times vary by location.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>How can I track my order?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Once your order ships, you'll receive a tracking number via email. You can also track your order by logging into your account and viewing your order history.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Do you offer gift wrapping?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes! We offer beautiful gift wrapping for an additional RM12 per item. You can select this option during checkout. Perfect for birthdays, festivals, and special occasions!</p>
                </div>
            </div>
        </div>
        
        <div class="faq-cta">
            <p>Can't find what you're looking for? <a href="#contact-form">Send us a message</a> and we'll get back to you quickly!</p>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="container">
        <div class="map-content">
            <h2>Visit Our Store</h2>
            <p>Come see our amazing selection in person!</p>
            
            <div class="map-container">
                <!-- Placeholder for Google Maps integration -->
                <div class="map-placeholder">
                    <i class="fas fa-map"></i>
                    <p>Interactive Map Coming Soon</p>
                    <p>Level 3, Pavilion Kuala Lumpur, 168 Jalan Bukit Bintang, 55100 KL</p>
                </div>
            </div>
            
            <div class="store-info">
                <div class="info-item">
                    <h4>Store Hours</h4>
                    <ul>
                        <li>Monday - Sunday: 10:00 AM - 10:00 PM</li>
                        <li>Public Holidays: 10:00 AM - 8:00 PM</li>
                    </ul>
                </div>
                
                <div class="info-item">
                    <h4>Parking</h4>
                    <p>Ample parking available at Pavilion KL</p>
                </div>
                
                <div class="info-item">
                    <h4>Accessibility</h4>
                    <p>Our store is fully accessible with lifts and wide aisles</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Support Hours Section -->
<section class="support-hours-section">
    <div class="container">
        <div class="support-hours-content">
            <h2>Customer Support Hours</h2>
            <div class="hours-grid">
                <div class="hours-item">
                    <h3>Phone Support</h3>
                    <p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM MYT</p>
                    <p><strong>Saturday:</strong> 10:00 AM - 4:00 PM MYT</p>
                    <p><strong>Sunday:</strong> Closed</p>
                </div>
                
                <div class="hours-item">
                    <h3>Email Support</h3>
                    <p><strong>Response Time:</strong> Within 24 hours</p>
                    <p><strong>Best Time:</strong> 9:00 AM - 5:00 PM MYT</p>
                </div>
                
                <div class="hours-item">
                    <h3>Live Chat</h3>
                    <p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM MYT</p>
                    <p><strong>Saturday:</strong> 10:00 AM - 4:00 PM MYT</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';
?> 