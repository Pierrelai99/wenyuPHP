   <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About FishyWishy Seafood Store</h3>
                    <p>We're passionate about bringing the freshest catch from ocean to table. Our carefully sourced selection of premium seafood ensures quality, freshness, and delicious flavors for seafood lovers everywhere.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="shipping.php">Shipping Info</a></li>
                        <li><a href="returns.php">Returns & Exchanges</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms & Conditions</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="faq.php">FAQ</a></li>
                        <li><a href="size-guide.php">Preparation Guide</a></li>
                        <li><a href="gift-cards.php">Gift Cards</a></li>
                        <li><a href="wishlist.php">Wishlist</a></li>
                        <li><a href="track-order.php">Track Order</a></li>
                        <li><a href="support.php">Customer Support</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Newsletter</h3>
                    <p>Subscribe to get special offers, fresh catch updates, and exclusive seafood deals delivered to your inbox.</p>
                    <form class="newsletter-form" action="newsletter.php" method="POST">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <p>&copy; <?php echo date('Y'); ?> FishyWishy Seafood Store. All rights reserved.</p>
                </div>
                <div class="footer-bottom-right">
                    <div class="payment-methods">
                        <span>We Accept:</span>
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-amex"></i>
                        <i class="fab fa-cc-paypal"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </button>

    
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
