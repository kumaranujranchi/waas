<?php
/**
 * Terms & Conditions Page
 */

$pageTitle = 'Terms & Conditions | SiteOnSub';
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';
?>

<main class="flex-1 py-16 px-6 md:px-20">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-[#0f0e1b] dark:text-white mb-4">
                Terms & Conditions
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
                    Welcome to SiteOnSub. By purchasing or using any website, software, or service offered by SiteOnSub
                    ("we", "our", "us"), you ("client", "user", "customer") agree to the following Terms & Conditions.
                    These terms are designed to ensure complete transparency between SiteOnSub and our customers.
                </p>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-12 font-semibold">
                    Please read them carefully before subscribing to any service.
                </p>

                <!-- Section 1 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">1</span>
                        Nature of Service (Website as a Service – WaaS)
                    </h2>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                            <span>SiteOnSub provides websites and software on a subscription basis, not as a one-time
                                sale.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                            <span>You are subscribing to a managed digital service that includes hosting, maintenance,
                                and support as defined in your selected plan.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                            <span>Ownership of the service remains with SiteOnSub unless explicitly stated
                                otherwise.</span>
                        </li>
                    </ul>
                </div>

                <!-- Section 2 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">2</span>
                        External & Third-Party Services
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        Any external or third-party services required for your website or software are not included in
                        your subscription plan and must be paid directly by the customer.
                    </p>
                    <p class="text-gray-600 dark:text-gray-300 mb-3 font-semibold">This includes, but is not limited to:
                    </p>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-300 ml-6 list-disc">
                        <li>Payment gateway charges</li>
                        <li>WhatsApp API services</li>
                        <li>SMS, Email, or OTP services</li>
                        <li>Third-party APIs or integrations</li>
                        <li>Paid plugins, tools, or licenses</li>
                    </ul>
                    <p class="text-gray-600 dark:text-gray-300 mt-4 italic">
                        SiteOnSub may assist with integration, but all external service costs are the customer's
                        responsibility.
                    </p>
                </div>

                <!-- Section 3 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">3</span>
                        What Is Included in Your Plan
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        Unless otherwise mentioned, the following services are included in your active subscription
                        plan:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">Website hosting</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">Database cost and management</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">Server maintenance</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">Security updates</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">Bug fixing</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">Minor code changes</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">On-page SEO (basic)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">Page additions (per plan)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">Regular maintenance</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-sm">check</span>
                            <span class="text-gray-600 dark:text-gray-300">Uptime monitoring</span>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mt-4 italic">
                        Anything beyond the defined scope of your plan may be charged additionally after discussion and
                        approval.
                    </p>
                </div>

                <!-- Section 4 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">4</span>
                        Domain Name Policy
                    </h2>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-500 mt-1 text-sm">info</span>
                            <span>The domain name is <strong>not included</strong> in any plan.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                            <span>The customer must purchase and own the domain name separately.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                            <span>If SiteOnSub purchases the domain on your behalf, the cost will be billed to the
                                customer.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                            <span>Domain renewal responsibility remains with the customer.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-500 mt-1 text-sm">warning</span>
                            <span>SiteOnSub is not responsible for domain expiration, suspension, or loss caused by
                                non-renewal.</span>
                        </li>
                    </ul>
                </div>

                <!-- Section 5 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">5</span>
                        Design & Customization Limitations
                    </h2>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                            <span>Each plan has specific design and customization limits.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                            <span>Design changes and layouts are provided as per the selected plan.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-500 mt-1 text-sm">info</span>
                            <span>Major UI/UX changes, advanced animations, or unique custom designs beyond plan scope
                                will incur additional charges.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                            <span>Design approval is required before development begins.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-500 mt-1 text-sm">info</span>
                            <span>Repeated revisions beyond reasonable limits may be chargeable.</span>
                        </li>
                    </ul>
                </div>

                <!-- Section 6 -->
                <div class="mb-10 bg-red-50 dark:bg-red-500/10 rounded-xl p-6 border-l-4 border-red-500">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-red-500/20 text-red-600 font-black text-lg">6</span>
                        Payment & Subscription Policy
                    </h2>
                    <ul class="space-y-3 text-gray-700 dark:text-gray-200">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-600 mt-1 text-sm">payments</span>
                            <span>Subscription fees must be paid in advance as per the selected billing cycle (monthly,
                                6-month, or yearly).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-600 mt-1 text-sm">warning</span>
                            <span><strong>If payment is not received within 30 days of the due date, your website and
                                    all associated data may be permanently deleted from our servers without further
                                    notice.</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-600 mt-1 text-sm">error</span>
                            <span>SiteOnSub is not responsible for data loss due to non-payment.</span>
                        </li>
                    </ul>
                </div>

                <!-- Sections 7-14 -->
                <?php
                $sections = [
                    [
                        'number' => 7,
                        'title' => 'No Transfer of Service',
                        'content' => [
                            'The subscribed service cannot be transferred to another website, domain, business, or individual.',
                            'Each subscription is valid only for one website and one business entity.'
                        ]
                    ],
                    [
                        'number' => 8,
                        'title' => 'Refund Policy',
                        'content' => [
                            'No refund will be issued once the website goes live.',
                            'You may request a refund only before the website is made live, subject to work completed till that stage.',
                            'Any setup, consultation, or work already done may be deducted from the refundable amount.'
                        ]
                    ],
                    [
                        'number' => 9,
                        'title' => 'Source Code Policy',
                        'content' => [
                            'The website source code is not included by default in the subscription.',
                            'Customers may request the complete source code by paying a nominal one-time fee.',
                            'Once source code is delivered, SiteOnSub is not responsible for changes made outside our platform.'
                        ]
                    ],
                    [
                        'number' => 10,
                        'title' => 'Content Responsibility',
                        'content' => [
                            'All text, images, videos, and materials provided by the customer must be legally owned or licensed.',
                            'SiteOnSub is not responsible for copyright violations caused by customer-provided content.',
                            'If content is created by SiteOnSub, it will be based on information provided by the customer.'
                        ]
                    ],
                    [
                        'number' => 11,
                        'title' => 'Suspension & Termination',
                        'warning' => true,
                        'intro' => 'SiteOnSub reserves the right to suspend or terminate services if:',
                        'content' => [
                            'The service is used for illegal activities',
                            'Payment terms are violated',
                            'The website contains prohibited or harmful content',
                            'There is misuse of server resources'
                        ],
                        'footer' => 'No refund will be issued in such cases.'
                    ],
                    [
                        'number' => 12,
                        'title' => 'Limitation of Liability',
                        'intro' => 'SiteOnSub shall not be liable for:',
                        'content' => [
                            'Business losses',
                            'Revenue loss',
                            'Data loss due to third-party services',
                            'Downtime caused by external providers',
                            'SEO ranking fluctuations'
                        ],
                        'footer' => 'We provide best-effort service but do not guarantee specific business outcomes.'
                    ],
                    [
                        'number' => 13,
                        'title' => 'Changes to Terms',
                        'content' => [
                            'SiteOnSub may update these Terms & Conditions at any time.',
                            'Continued use of services after updates implies acceptance of the revised terms.'
                        ]
                    ],
                    [
                        'number' => 14,
                        'title' => 'Contact & Support',
                        'intro' => 'For any questions regarding these Terms & Conditions, you can contact us at:',
                        'contact' => true
                    ]
                ];

                foreach ($sections as $section):
                    ?>
                    <div
                        class="mb-10 <?php echo isset($section['warning']) ? 'bg-yellow-50 dark:bg-yellow-500/10 rounded-xl p-6 border-l-4 border-yellow-500' : ''; ?>">
                        <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                            <span
                                class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">
                                <?php echo $section['number']; ?>
                            </span>
                            <?php echo $section['title']; ?>
                        </h2>

                        <?php if (isset($section['intro'])): ?>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">
                                <?php echo $section['intro']; ?>
                            </p>
                        <?php endif; ?>

                        <?php if (isset($section['contact'])): ?>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">email</span>
                                    <span class="text-gray-600 dark:text-gray-300"><strong>Email:</strong>
                                        support@siteonsub.com</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">phone</span>
                                    <span class="text-gray-600 dark:text-gray-300"><strong>Phone:</strong> +91 XXX XXX
                                        XXXX</span>
                                </div>
                            </div>
                        <?php elseif (isset($section['content'])): ?>
                            <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                                <?php foreach ($section['content'] as $item): ?>
                                    <li class="flex items-start gap-3">
                                        <span class="material-symbols-outlined text-primary mt-1 text-sm">check_circle</span>
                                        <span>
                                            <?php echo $item; ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (isset($section['footer'])): ?>
                            <p class="text-gray-600 dark:text-gray-300 mt-4 font-semibold">
                                <?php echo $section['footer']; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Final Statement -->
                <div
                    class="mt-12 bg-gradient-to-br from-primary/10 to-purple-500/10 rounded-2xl p-8 border-2 border-primary/20">
                    <h3 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">verified</span>
                        Final Transparency Statement
                    </h3>
                    <p class="text-gray-700 dark:text-gray-200 leading-relaxed">
                        By subscribing to SiteOnSub, you acknowledge that you have read, understood, and agreed to these
                        Terms & Conditions and understand the Website as a Service model completely.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>