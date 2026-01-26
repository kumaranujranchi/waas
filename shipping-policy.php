<?php
/**
 * Shipping (Delivery) Policy Page
 */

$pageTitle = 'Shipping & Delivery Policy | SiteOnSub';
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';
?>

<main class="flex-1 py-16 px-6 md:px-20">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-[#0f0e1b] dark:text-white mb-4">
                Shipping & Delivery Policy
            </h1>
            <p class="text-gray-500 dark:text-gray-400">
                Last Updated:
                <?php echo date('F d, Y'); ?>
            </p>
        </div>

        <!-- Content -->
        <div class="prose prose-lg dark:prose-invert max-w-none">
            <div
                class="bg-white dark:bg-[#1c1b2e] rounded-2xl p-8 md:p-12 border border-gray-200 dark:border-white/10 shadow-sm">
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-8">
                    SiteOnSub is a digital marketplace and service provider. As such, we do not ship physical products.
                    This policy outlines how our digital goods, software, and services are delivered to you.
                </p>

                <!-- Section 1 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">1</span>
                        Digital Products & Software
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        For any downloadable software, templates, or digital assets purchased through our marketplace:
                    </p>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-green-500 mt-1 text-sm">check_circle</span>
                            <span><strong>Instant Access:</strong> Upon successful payment confirmation, you will
                                receive immediate access to download your files.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-green-500 mt-1 text-sm">check_circle</span>
                            <span><strong>Email Confirmation:</strong> An email containing your download links and
                                license keys (if applicable) will be sent to your registered email address
                                instantly.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-green-500 mt-1 text-sm">check_circle</span>
                            <span><strong>Dashboard Access:</strong> You can also access your purchased items at any
                                time from your account dashboard under the "My Downloads" or "Active Subscriptions"
                                section.</span>
                        </li>
                    </ul>
                </div>

                <!-- Section 2 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">2</span>
                        Website as a Service (WaaS)
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        For subscription-based website plans and custom development services:
                    </p>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">schedule</span>
                            <span><strong>Setup Timeline:</strong> Standard WaaS sites are typically provisioned and
                                ready for your initial review within <strong>24 to 48 hours</strong> of purchase,
                                depending on the complexity of the selected template.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">engineering</span>
                            <span><strong>Custom Development:</strong> For custom requests, delivery timelines will be
                                agreed upon during the consultation phase and outlined in your project proposal.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">verified_user</span>
                            <span><strong>Handover:</strong> Once setup is complete, login credentials and
                                administrative access details will be securely sent to your registered email.</span>
                        </li>
                    </ul>
                </div>

                <!-- Section 3 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">3</span>
                        Consultation Services
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        Consultation calls are "delivered" via video conferencing (e.g., Google Meet, Zoom) at the
                        scheduled time.
                        You will receive a meeting link via email immediately after booking. If you need to reschedule,
                        please refer to our rescheduling policy or contact support.
                    </p>
                </div>

                <!-- Section 4 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">4</span>
                        Failed Delivery & Support
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                        If you do not receive your digital product link or service execution within the specified
                        timeframe:
                    </p>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-500 mt-1 text-sm">warning</span>
                            <span>Check your email's spam/junk folder.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-500 mt-1 text-sm">warning</span>
                            <span>Ensure your payment was successfully processed.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">contact_support</span>
                            <span>Contact our support team immediately. We will resolve delivery issues as a top
                                priority.</span>
                        </li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div
                    class="mt-12 bg-gray-50 dark:bg-white/5 rounded-2xl p-8 border border-gray-200 dark:border-white/10">
                    <h3 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4">
                        Contact Us
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        For any questions regarding the delivery of your service or product:
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">email</span>
                            <span class="text-gray-600 dark:text-gray-300"><strong>Email:</strong>
                                support@siteonsub.com</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">call</span>
                            <span class="text-gray-600 dark:text-gray-300"><strong>phone:</strong> +91 9525230232</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>