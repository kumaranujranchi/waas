<!-- Footer -->
<footer class="bg-white dark:bg-background-dark border-t border-[#e8e8f3] dark:border-white/10 py-12">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12">
            <div class="col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-xl">layers</span>
                    </div>
                    <h2 class="text-[#0f0e1b] dark:text-white text-xl font-bold">
                        <?php echo SITE_NAME; ?>
                    </h2>
                </div>
                <p class="text-[#545095] dark:text-white/60 text-sm max-w-xs mb-6 leading-relaxed">
                    Modern websites and enterprise-grade software delivered as a service. No dev costs, no maintenance
                    headaches.
                </p>
                <div class="flex gap-4">
                    <a class="w-10 h-10 rounded-full bg-background-light dark:bg-white/5 flex items-center justify-center hover:bg-primary hover:text-white transition-all"
                        href="#">
                        <span class="material-symbols-outlined text-xl">public</span>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-background-light dark:bg-white/5 flex items-center justify-center hover:bg-primary hover:text-white transition-all"
                        href="#">
                        <span class="material-symbols-outlined text-xl">alternate_email</span>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="text-[#0f0e1b] dark:text-white font-bold mb-6">Solutions</h4>
                <ul class="flex flex-col gap-4 text-sm text-[#545095] dark:text-white/60">
                    <li><a class="hover:text-primary" href="<?php echo baseUrl('index.php'); ?>">Websites</a></li>
                    <li><a class="hover:text-primary" href="<?php echo baseUrl('index.php'); ?>">E-commerce</a></li>
                    <li><a class="hover:text-primary" href="<?php echo baseUrl('index.php'); ?>">CRM Software</a></li>
                    <li><a class="hover:text-primary" href="<?php echo baseUrl('index.php'); ?>">Booking Engines</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="text-[#0f0e1b] dark:text-white font-bold mb-6">Company</h4>
                <ul class="flex flex-col gap-4 text-sm text-[#545095] dark:text-white/60">
                    <li><a class="hover:text-primary" href="#">About Us</a></li>
                    <li><a class="hover:text-primary" href="#">Pricing</a></li>
                    <li><a class="hover:text-primary" href="#">Partners</a></li>
                    <li><a class="hover:text-primary" href="<?php echo baseUrl('consultation.php'); ?>">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-[#0f0e1b] dark:text-white font-bold mb-6">Legal</h4>
                <ul class="flex flex-col gap-4 text-sm text-[#545095] dark:text-white/60">
                    <li><a class="hover:text-primary" href="#">Privacy Policy</a></li>
                    <li><a class="hover:text-primary" href="#">Terms of Service</a></li>
                    <li><a class="hover:text-primary" href="#">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
        <div
            class="mt-16 pt-8 border-t border-[#e8e8f3] dark:border-white/10 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-sm text-[#545095] dark:text-white/60">©
                <?php echo date('Y'); ?>
                <?php echo SITE_NAME; ?>. All rights reserved.
            </p>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-accent-green animate-pulse"></span>
                <span class="text-xs font-medium text-[#545095] dark:text-white/60">All systems operational</span>
            </div>
        </div>
    </div>
</footer>

</body>

</html>