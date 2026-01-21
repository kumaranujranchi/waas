<!-- Footer -->
<footer class="bg-white dark:bg-background-dark border-t border-[#e8e8f3] dark:border-white/10 py-12">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12">
            <div class="col-span-2">
                <div class="flex items-center gap-3 mb-2">
                    <a href="<?php echo baseUrl('index.php'); ?>">
                        <img src="<?php echo baseUrl('assets/images/logo.png'); ?>" alt="SiteOnSub Logo"
                            class="h-10 w-auto">
                    </a>
                </div>
                <p class="text-xs font-bold text-primary mb-6">
                    <?php echo defined('SITE_TAGLINE') ? SITE_TAGLINE : 'WaaS Marketplace'; ?>
                </p>
                <p class="text-[#545095] dark:text-white/60 text-sm max-w-xs mb-6 leading-relaxed">
                    Modern websites and enterprise-grade software delivered as a service. No dev costs, no maintenance
                    headaches.
                </p>
                <div class="flex gap-3">
                    <a class="w-10 h-10 rounded-full bg-background-light dark:bg-white/5 flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-all"
                        href="#" title="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-background-light dark:bg-white/5 flex items-center justify-center hover:bg-[#1DA1F2] hover:text-white transition-all"
                        href="#" title="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-background-light dark:bg-white/5 flex items-center justify-center hover:bg-[#0A66C2] hover:text-white transition-all"
                        href="#" title="LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </a>
                    <a class="w-10 h-10 rounded-full bg-background-light dark:bg-white/5 flex items-center justify-center hover:bg-[#E4405F] hover:text-white transition-all"
                        href="#" title="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                        </svg>
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
                    <li><a class="hover:text-primary" href="<?php echo baseUrl('privacy-policy.php'); ?>">Privacy
                            Policy</a></li>
                    <li><a class="hover:text-primary" href="<?php echo baseUrl('terms.php'); ?>">Terms & Conditions</a>
                    </li>
                    <li><a class="hover:text-primary" href="<?php echo baseUrl('cookie-policy.php'); ?>">Cookie
                            Policy</a></li>
                </ul>
            </div>
        </div>
        <div
            class="mt-16 pt-8 border-t border-[#e8e8f3] dark:border-white/10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <p class="text-sm text-[#545095] dark:text-white/60">©
                    <?php echo date('Y'); ?>
                    SiteOnSub. All rights reserved.
                </p>
                <!-- Secure Payment Icons -->
                <div class="flex items-center gap-3 opacity-90 transition-all">
                    <!-- Visa -->
                    <svg class="h-8 w-auto" viewBox="0 0 32 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.936 0.165039L8.724 9.87104H4.568L1.604 2.80904C1.258 1.48104 0.246 0.999039 0 0.851039L0.05 0.630039H4.726C5.358 0.630039 5.922 1.05604 6.066 1.77804L7.198 7.85404L10.038 1.78204C10.608 0.564039 10.596 0.165039 9.864 0.165039H12.936ZM24.462 6.78404C24.478 4.24604 20.898 4.09904 20.938 2.94904C20.952 2.60004 21.284 2.23504 22.128 2.12504C22.562 2.07204 23.75 2.01604 25.132 2.63704L25.61 0.496039C24.966 0.270039 24.136 0.165039 23.116 0.165039C20.254 0.165039 18.238 1.66604 18.23 3.86804C18.22 5.48504 19.678 6.38804 20.768 6.91404C21.884 7.45204 22.258 7.79404 22.256 8.32404C22.25 9.15004 21.282 9.53104 20.378 9.53104C19.348 9.53104 18.272 9.24604 17.59 8.94004L17.158 11.082C17.808 11.376 19.028 11.625 20.282 11.642C23.27 11.642 25.228 10.176 25.244 7.93504L24.462 6.78404ZM31.624 0.334039H28.98C28.168 0.334039 27.502 0.568039 27.2 1.28404L23.336 10.468C23.758 10.468 25.32 10.468 25.864 10.468C26.062 9.58904 26.262 9.28204 26.758 9.28204H29.746C29.832 9.61904 30.016 10.468 30.016 10.468H32.61L31.624 0.334039ZM27.484 7.82804C27.674 7.15204 28.324 4.54204 28.324 4.54204C28.298 4.59004 28.694 5.92204 28.878 6.64904C28.932 6.85804 29.074 7.55004 29.074 7.55004L28.618 7.82804H27.484ZM17.652 0.334039L15.352 11.524H12.986L15.286 0.334039H17.652Z" fill="#1434CB"/>
                    </svg>

                    <!-- Mastercard -->
                    <svg class="h-8 w-auto" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="12" fill="#EB001B" fill-opacity="0.9"/>
                        <circle cx="26" cy="12" r="12" fill="#F79E1B" fill-opacity="0.9"/>
                    </svg>

                    <!-- Amex -->
                    <svg class="h-7 w-auto rounded" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="36" height="24" fill="#006FCF"/>
                        <path d="M22.02 5.06H25.04L26.15 7.64L27.27 5.06H29.98L27.45 10.51L27.46 10.51L29.74 15.11H27.09L26.19 13.06L25.26 15.11H22.45L24.81 10.51L22.02 5.06ZM12.01 5.06V15.11H14.86V9.41H16.63L18.44 14.86L20.24 9.4H21.73V15.11H24.38V5.06H21.46L18.42 13.23L15.37 5.06H12.01ZM8.6 12.35L7.23 8.87L5.85 12.36H8.6ZM3.51 5.06L7.33 15.11H10.15L8.73 11.51H5.73L4.31 15.11H1.54L5.35 5.06H3.51Z" fill="white"/>
                    </svg>

                     <!-- PayPal -->
                    <svg class="h-6 w-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.845 5.253C18.243 4.548 17.26 4.093 16.037 4.093H9.411C9.074 4.093 8.783 4.331 8.732 4.664L6.924 16.142C6.903 16.275 7.006 16.395 7.141 16.395H10.222L9.822 18.932C9.771 19.265 10.029 19.558 10.366 19.558H14.129C14.42 19.558 14.673 19.349 14.717 19.062L14.793 18.577L15.201 15.989C15.228 15.82 15.374 15.696 15.545 15.696H15.972C18.667 15.696 20.812 14.606 21.217 10.873C21.393 9.255 20.931 7.697 18.845 5.253Z" fill="#003087"/>
                        <path d="M18.303 5.76C17.771 5.138 16.903 4.737 15.824 4.737H9.258C8.921 4.737 8.63 4.975 8.579 5.308L6.829 16.425C6.808 16.558 6.911 16.678 7.046 16.678H9.313L9.697 14.238C9.748 13.905 10.039 13.667 10.376 13.667H11.834C15.845 13.667 17.971 12.049 18.574 8.225C18.729 7.236 18.796 6.335 18.303 5.76Z" fill="#009cde"/>
                        <path d="M9.697 14.238L8.277 16.425C8.256 16.558 8.359 16.678 8.494 16.678H11.575L11.175 19.215C11.124 19.548 11.382 19.841 11.719 19.841H15.482C15.773 19.841 16.026 19.632 16.07 19.345L16.146 18.86L16.554 16.272C16.581 16.103 16.727 15.979 16.898 15.979H17.325C20.02 15.979 21.6 14.939 21.986 12.018C22.072 11.378 22.094 10.835 22.052 10.395C21.372 13.119 18.966 14.238 15.178 14.238H10.376C10.039 14.238 9.748 14.05 9.697 14.238Z" fill="#012169"/>
                    </svg>
                    <div
                        class="flex items-center gap-1 border border-slate-200 dark:border-white/10 rounded px-1.5 py-0.5 bg-white">
                        <span class="material-symbols-outlined text-[16px] text-green-600">lock</span>
                        <span class="text-[10px] font-bold text-slate-600">SSL SECURE</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-accent-green animate-pulse"></span>
                <span class="text-xs font-medium text-[#545095] dark:text-white/60">
                    A group of <a href="https://synergybrandarchitect.in" target="_blank"
                        class="hover:text-primary transition-colors">Synergy Brand Architect</a> Co.
                </span>
            </div>
        </div>
    </div>
</footer>

</body>

</html>