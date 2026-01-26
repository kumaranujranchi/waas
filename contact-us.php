<?php
/**
 * Contact Us Page
 */

// Include header
$pageTitle = 'Contact Us | SiteOnSub';
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
                Get in <span
                    class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-purple-600">Touch</span>
            </h1>
            <p class="text-xl text-[#545095] dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                Have questions or need assistance? We're here to help you build and grow your digital presence.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-[1200px] mx-auto px-6 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            <!-- Contact Information -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-6">Contact Information</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                        Reach out to us through any of the following channels. Our support team is always ready to
                        assist you.
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Phone -->
                    <div
                        class="flex items-start gap-4 p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 transition-transform hover:scale-[1.02]">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary text-2xl">call</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-1">Phone Number</h3>
                            <a href="tel:9525230232"
                                class="text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                                +91 9525230232
                            </a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div
                        class="flex items-start gap-4 p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 transition-transform hover:scale-[1.02]">
                        <div class="w-12 h-12 rounded-full bg-purple-500/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-purple-600 text-2xl">mail</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-1">Email Address</h3>
                            <a href="mailto:support@siteonsub.com"
                                class="text-gray-600 dark:text-gray-400 hover:text-purple-600 transition-colors">
                                support@siteonsub.com
                            </a>
                        </div>
                    </div>

                    <!-- Office Address (Optional Placeholder) -->
                    <div
                        class="flex items-start gap-4 p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 transition-transform hover:scale-[1.02]">
                        <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-green-600 text-2xl">location_on</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-1">Synergy Brand Architect
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Digital Solutions Partner<br>
                                Always online for you.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div
                class="bg-white dark:bg-white/5 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/10 shadow-xl shadow-gray-200/50 dark:shadow-none">
                <h3 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-6">Send us a Message</h3>
                <form action="#" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">Your
                                Name</label>
                            <input type="text" id="name" name="name"
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all dark:text-white"
                                placeholder="John Doe" required>
                        </div>
                        <div class="space-y-2">
                            <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">Email
                                Address</label>
                            <input type="email" id="email" name="email"
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all dark:text-white"
                                placeholder="john@example.com" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="subject"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                        <input type="text" id="subject" name="subject"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all dark:text-white"
                            placeholder="How can we help?" required>
                    </div>

                    <div class="space-y-2">
                        <label for="message"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                        <textarea id="message" name="message" rows="4"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all dark:text-white resize-none"
                            placeholder="Write your message here..." required></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/25 hover:shadow-primary/40 transition-all transform hover:-translate-y-0.5">
                        Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Map/Location Section (Optional/Decorative) -->
    <div class="bg-[#0f0e1b] py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-purple-600/10"></div>
        <div class="max-w-[1200px] mx-auto px-6 relative z-10 text-center">
            <span class="material-symbols-outlined text-6xl text-primary mb-6">support_agent</span>
            <h2 class="text-3xl font-bold text-white mb-4">We are here for you 24/7</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Whether you need technical support or have a sales inquiry, our team is ready to assist you round the
                clock.
            </p>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>