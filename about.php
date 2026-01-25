<?php
/**
 * About Us Page
 */

// Include header
$pageTitle = 'About Us | SiteOnSub';
include __DIR__ . '/includes/header.php';
?>

<main class="flex-1 w-full bg-white dark:bg-background-dark">
    <!-- Hero Section -->
    <div class="relative py-20 bg-gray-50 dark:bg-white/5 overflow-hidden">
        <div
            class="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] dark:bg-grid-slate-700/25 dark:[mask-image:linear-gradient(0deg,rgba(255,255,255,0.1),rgba(255,255,255,0.5))]">
        </div>
        <div class="max-w-[1200px] mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-[#0f0e1b] dark:text-white mb-6 tracking-tight">
                Building Websites the <span
                    class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-purple-600">Smarter Way</span>
            </h1>
            <p class="text-xl text-[#545095] dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Welcome to <span class="font-bold text-primary">siteonsub.com</span>, a product of <span
                    class="font-bold">Synergy Brand Architect</span>-where we are redefining how businesses build,
                manage, and grow their digital presence.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-[1000px] mx-auto px-6 py-20 space-y-20">

        <!-- Introduction -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-6">Our Mission</h2>
                <div class="space-y-4 text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                    <p>
                        Traditional website development is often expensive, time-consuming, and difficult to maintain.
                        We created <strong>SiteOnSub</strong> to change that.
                    </p>
                    <p>
                        Our mission is simple: make high-quality websites and business software accessible through a
                        flexible, subscription-based model-without the burden of heavy upfront costs or technical
                        headaches.
                    </p>
                </div>
            </div>
            <div class="bg-gray-100 dark:bg-white/5 rounded-[2rem] p-8 md:p-12">
                <span class="material-symbols-outlined text-6xl text-primary mb-4">rocket_launch</span>
                <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2">Future-Ready</h3>
                <p class="text-gray-500 dark:text-gray-400">We help businesses focus on growth, not technology.</p>
            </div>
        </section>

        <!-- Who We Are -->
        <section>
            <div class="bg-primary/5 dark:bg-primary/10 rounded-[2.5rem] p-8 md:p-16 border border-primary/10">
                <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-6">Who We Are</h2>
                <div class="space-y-6 text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    <p>
                        <strong>Synergy Brand Architect</strong> is a digital solutions company focused on creating
                        scalable, future-ready web and software platforms for modern businesses.
                    </p>
                    <p>
                        With SiteOnSub, we bring together design, development, hosting, maintenance, and support into
                        one transparent service. We believe websites should not be a one-time project. They should
                        evolve, improve, and scale as your business grows. That’s why we built SiteOnSub as a
                        <strong>Website as a Service (WaaS)</strong> platform.
                    </p>
                </div>
            </div>
        </section>

        <!-- What We Do -->
        <section>
            <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-10 text-center">What We Do</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 shadow-sm hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-4xl text-blue-500 mb-4">calendar_month</span>
                    <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-3">Subscription-based websites</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Monthly, 6-month, and yearly plans suitable for
                        any budget.</p>
                </div>
                <!-- Card 2 -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 shadow-sm hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-4xl text-purple-500 mb-4">code</span>
                    <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-3">Custom-built websites</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Not WordPress, Wix, or templates. Tailor-made
                        for performance.</p>
                </div>
                <!-- Card 3 -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 shadow-sm hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-4xl text-green-500 mb-4">inventory_2</span>
                    <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-3">Business Software</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Ready-to-use tools like CRM, billing, and
                        management systems.</p>
                </div>
                <!-- Card 4 -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 shadow-sm hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-4xl text-orange-500 mb-4">dns</span>
                    <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-3">Fully Managed</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Hosting, database, maintenance, and security all
                        included.</p>
                </div>
                <!-- Card 5 -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 shadow-sm hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-4xl text-pink-500 mb-4">support_agent</span>
                    <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-3">Ongoing Support</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Continuous improvements and dedicated
                        assistance.</p>
                </div>
            </div>
        </section>

        <!-- Why Exists & Philosophy -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <div>
                <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-6">Why SiteOnSub Exists</h2>
                <div class="space-y-4 text-gray-600 dark:text-gray-400">
                    <p>We noticed a common problem:</p>
                    <ul class="space-y-3 pl-4 border-l-2 border-primary/20">
                        <li>Businesses pay a large amount to build a website.</li>
                        <li>After launch, they are left alone with hosting, bugs, updates, and security.</li>
                        <li>Any small change becomes costly and frustrating.</li>
                    </ul>
                    <p class="mt-4 font-medium text-[#0f0e1b] dark:text-white">
                        SiteOnSub solves this by offering websites like a service, not a product.
                        You subscribe, we manage everything.
                    </p>
                </div>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-6">Our Philosophy</h2>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong class="block text-[#0f0e1b] dark:text-white">Transparency first</strong>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Clear pricing, clear scope, no hidden
                                surprises.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong class="block text-[#0f0e1b] dark:text-white">Subscription over ownership
                                stress</strong>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Predictable and affordable.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong class="block text-[#0f0e1b] dark:text-white">Long-term partnership</strong>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Not just project delivery.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                        <div>
                            <strong class="block text-[#0f0e1b] dark:text-white">Quality without compromise</strong>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Custom builds, modern tech, real
                                performance.</span>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <!-- Commitment & Contact -->
        <section class="bg-[#0f0e1b] rounded-[2.5rem] p-8 md:p-16 text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-purple-600/20"></div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold mb-6">Our Commitment</h2>
                <p class="text-white/80 mb-8 leading-relaxed">
                    We are committed to delivering reliable, high-performance websites and software, keeping our
                    platform secure and up to date, and providing honest communication and support.
                </p>

                <div class="h-px w-full bg-white/10 mb-8"></div>

                <h3 class="text-2xl font-bold mb-6">Get in Touch</h3>
                <p class="mb-8 text-white/80">Have questions or want to learn more about how SiteOnSub works?</p>

                <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                    <a href="mailto:support@siteonesub.com"
                        class="flex items-center gap-2 bg-white text-[#0f0e1b] px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors">
                        <span class="material-symbols-outlined">mail</span>
                        support@siteonesub.com
                    </a>
                </div>
                <p class="mt-8 text-sm text-white/50">
                    🏢 Company: Synergy Brand Architect<br>
                    We’re here to help you build and grow—the smarter way.
                </p>
            </div>
        </section>

    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>