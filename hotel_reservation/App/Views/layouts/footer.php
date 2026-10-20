    </main>
    
    <footer class="text-center" style="padding: 2rem; background: white; border-top: 1px solid #e2e8f0; margin-top: 3rem;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            <p style="margin: 0; color: #64748b;">© <?= date('Y') ?> LuxuryStay Hotel Management. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for internal links
        document.querySelectorAll('a[href^=\"#\"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                target?.scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Enhance table interactions
        document.querySelectorAll('table tbody tr').forEach(row => {
            row.style.transition = 'all 0.2s';
            row.addEventListener('mouseenter', () => {
                row.style.backgroundColor = '#f8fafc';
                row.style.transform = 'scale(1.01)';
            });
            row.addEventListener('mouseleave', () => {
                row.style.backgroundColor = 'transparent';
                row.style.transform = 'scale(1)';
            });
        });

        // Form focus states
        document.querySelectorAll('.form-group input, .form-group select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    </script>
</body>
</html>

