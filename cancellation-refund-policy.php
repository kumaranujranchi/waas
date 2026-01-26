<?php
/**
 * Cancellation & Refund Policy Page
 */

$pageTitle = 'Cancellation & Refund Policy | SiteOnSub';
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';
?>

<main class="flex-1 py-16 px-6 md:px-20">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-[#0f0e1b] dark:text-white mb-4">
                Cancellation & Refund Policy
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
                    At SiteOnSub, we strive to provide high-quality digital products and services. We value transparency
                    and want you to be fully informed about our cancellation and refund practices.
                </p>

                <!-- Section 1 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">1</span>
                        Subscription Services (WaaS)
                    </h2>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">cancel</span>
                            <span><strong>Cancellation:</strong> You may cancel your monthly or annual website
                                subscription at any time via your account dashboard or by contacting support. Your
                                access will continue until the end of the current billing cycle.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">payments</span>
                            <span><strong>Refunds:</strong> We offer a <strong>7-day money-back guarantee</strong> for
                                new WaaS subscriptions. If you are unsatisfied with the service within the first 7 days,
                                you can request a full refund. After 7 days, no refunds will be issued for partial
                                billing periods.</span>
                        </li>
                    </ul>
                </div>

                <!-- Section 2 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">2</span>
                        Digital Downloads & Software
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        Due to the nature of digital goods (software, templates, assets) which can be downloaded
                        instantly:
                    </p>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-500 mt-1 text-sm">block</span>
                            <span><strong>No Refunds:</strong> All sales of downloadable digital products are final and
                                non-refundable once the file has been accessed or downloaded.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">priority_high</span>
                            <span><strong>Exceptions:</strong> Refunds may be considered only if the file is technically
                                defective or corrupt, and our support team is unable to resolve the issue within a
                                reasonable timeframe.</span>
                        </li>
                    </ul>
                </div>

                <!-- Section 3 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">3</span>
                        Custom Development & Services
                    </h2>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">handshake</span>
                            <span><strong>Milestone Payments:</strong> For custom projects, payments are typically
                                milestone-based. Refunds are not issued for completed milestones that have been approved
                                by the client.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">event_busy</span>
                            <span><strong>Consultation Calls:</strong> Cancellations made at least 24 hours in advance
                                are eligible for a full refund or rescheduling. Cancellations within 24 hours of the
                                scheduled time are non-refundable.</span>
                        </li>
                    </ul>
                </div>

                <!-- Section 4 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">4</span>
                        How to Request a Refund
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        To request a cancellation or refund, please follow these steps:
                    </p>
                    <ol
                        class="list-decimal pl-5 space-y-3 text-gray-600 dark:text-gray-300 marker:text-primary marker:font-bold">
                        <li>Send an email to <strong>support@siteonsub.com</strong> with the subject line "Refund
                            Request - [Order ID]".</li>
                        <li>Include your order details and the reason for the request.</li>
                        <li>Our team will review your request and respond within 24-48 business hours.</li>
                    </ol>
                </div>

                <!-- Contact Section -->
                <div
                    class="mt-12 bg-gray-50 dark:bg-white/5 rounded-2xl p-8 border border-gray-200 dark:border-white/10">
                    <h3 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4">
                        Questions?
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        If you are unsure about our policy or have specific questions before making a purchase, please
                        contact us.
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