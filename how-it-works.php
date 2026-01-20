<?php
/**
 * How It Works Page
 */
$pageTitle = 'How It Works | SiteOnSub';
include __DIR__ . '/includes/header.php';
?>

<main class="flex-1">
    <!-- Hero Section -->
    <section class="relative py-20 px-6 overflow-hidden">
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Simple Process</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-[#0f0e1b] dark:text-white mb-6 leading-tight">
                Getting your website live is <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600">simple,
                    transparent, and hassle-free.</span>
            </h1>
            <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">
                No complex coding, no heavy upfront costs. Just a clear path to launching your digital business.
            </p>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-12 pb-24 px-6 md:px-12 relative">
        <div class="max-w-7xl mx-auto">

            <!-- Desktop Timeline (Horizontal) -->
            <div class="hidden lg:block relative">
                <!-- Connecting Line -->
                <div class="absolute top-12 left-0 w-full h-1 bg-gray-100 dark:bg-white/5 rounded-full -z-10"></div>
                <div
                    class="absolute top-12 left-0 w-full h-1 bg-gradient-to-r from-primary/20 via-purple-500/20 to-accent-green/20 rounded-full -z-10">
                </div>

                <div class="grid grid-cols-7 gap-4">
                    <!-- Step 1 -->
                    <div class="relative group">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-24 h-24 rounded-2xl bg-white dark:bg-[#1c1b2e] border-4 border-gray-50 dark:border-[#2d2c45] shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 relative z-10">
                                <span class="material-symbols-outlined text-4xl text-primary">event_note</span>
                            </div>
                            <h3 class="font-bold text-lg text-[#0f0e1b] dark:text-white mb-2">Book Consultation</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed px-2">
                                Share your goals and requirements with our team.
                            </p>
                            <div
                                class="absolute top-24 left-1/2 -translate-x-1/2 w-0.5 h-8 bg-gradient-to-b from-primary/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative group">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-24 h-24 rounded-2xl bg-white dark:bg-[#1c1b2e] border-4 border-gray-50 dark:border-[#2d2c45] shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 relative z-10">
                                <span class="material-symbols-outlined text-4xl text-purple-500">payments</span>
                            </div>
                            <h3 class="font-bold text-lg text-[#0f0e1b] dark:text-white mb-2">Choose & Pay</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed px-2">
                                Select a plan. No heavy upfront costs.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative group">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-24 h-24 rounded-2xl bg-white dark:bg-[#1c1b2e] border-4 border-gray-50 dark:border-[#2d2c45] shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 relative z-10">
                                <span class="material-symbols-outlined text-4xl text-blue-500">groups</span>
                            </div>
                            <h3 class="font-bold text-lg text-[#0f0e1b] dark:text-white mb-2">Plan Discussion</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed px-2">
                                Clarify scope, features, and timelines.
                            </p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative group">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-24 h-24 rounded-2xl bg-white dark:bg-[#1c1b2e] border-4 border-gray-50 dark:border-[#2d2c45] shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 relative z-10">
                                <span class="material-symbols-outlined text-4xl text-pink-500">palette</span>
                            </div>
                            <h3 class="font-bold text-lg text-[#0f0e1b] dark:text-white mb-2">Design & Content</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed px-2">
                                Brainstorm design direction and structure.
                            </p>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="relative group">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-24 h-24 rounded-2xl bg-white dark:bg-[#1c1b2e] border-4 border-gray-50 dark:border-[#2d2c45] shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 relative z-10">
                                <span class="material-symbols-outlined text-4xl text-orange-500">check_circle</span>
                            </div>
                            <h3 class="font-bold text-lg text-[#0f0e1b] dark:text-white mb-2">Final Approval</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed px-2">
                                Review and approve the proposed plan.
                            </p>
                        </div>
                    </div>

                    <!-- Step 6 -->
                    <div class="relative group">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-24 h-24 rounded-2xl bg-white dark:bg-[#1c1b2e] border-4 border-gray-50 dark:border-[#2d2c45] shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 relative z-10">
                                <span class="material-symbols-outlined text-4xl text-cyan-500">engineering</span>
                            </div>
                            <h3 class="font-bold text-lg text-[#0f0e1b] dark:text-white mb-2">Work Starts</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed px-2">
                                Development, setup, and configuration begin.
                            </p>
                        </div>
                    </div>

                    <!-- Step 7 -->
                    <div class="relative group">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-24 h-24 rounded-2xl bg-white dark:bg-[#1c1b2e] border-4 border-gray-50 dark:border-[#2d2c45] shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 relative z-10">
                                <span class="material-symbols-outlined text-4xl text-accent-green">rocket_launch</span>
                            </div>
                            <h3 class="font-bold text-lg text-[#0f0e1b] dark:text-white mb-2">Go Live</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed px-2">
                                Launch day! We handle hosting & support.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Timeline (Vertical) -->
            <div class="lg:hidden space-y-8 relative">
                <div class="absolute top-0 left-8 bottom-0 w-0.5 bg-gray-200 dark:bg-white/10"></div>

                <!-- Step 1 -->
                <div class="relative flex gap-6">
                    <div
                        class="flex-none w-16 h-16 rounded-xl bg-white dark:bg-[#1c1b2e] border-2 border-primary shadow-lg flex items-center justify-center z-10">
                        <span class="material-symbols-outlined text-2xl text-primary">event_note</span>
                    </div>
                    <div
                        class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex-1">
                        <span class="text-xs font-bold text-primary uppercase mb-1 block">Step 1</span>
                        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2">Book a Consultation</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Start by booking a free consultation call with our team. Share your business goals, ideas,
                            and requirements.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex gap-6">
                    <div
                        class="flex-none w-16 h-16 rounded-xl bg-white dark:bg-[#1c1b2e] border-2 border-purple-500 shadow-lg flex items-center justify-center z-10">
                        <span class="material-symbols-outlined text-2xl text-purple-500">payments</span>
                    </div>
                    <div
                        class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex-1">
                        <span class="text-xs font-bold text-purple-500 uppercase mb-1 block">Step 2</span>
                        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2">Choose & Pay</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Select the website or software service that fits your needs and proceed with the plan. No
                            heavy upfront cost.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative flex gap-6">
                    <div
                        class="flex-none w-16 h-16 rounded-xl bg-white dark:bg-[#1c1b2e] border-2 border-blue-500 shadow-lg flex items-center justify-center z-10">
                        <span class="material-symbols-outlined text-2xl text-blue-500">groups</span>
                    </div>
                    <div
                        class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex-1">
                        <span class="text-xs font-bold text-blue-500 uppercase mb-1 block">Step 3</span>
                        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2">Plan Discussion</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            We discuss the selected plan in detail, clarify scope, features, timelines, and finalize
                            everything.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative flex gap-6">
                    <div
                        class="flex-none w-16 h-16 rounded-xl bg-white dark:bg-[#1c1b2e] border-2 border-pink-500 shadow-lg flex items-center justify-center z-10">
                        <span class="material-symbols-outlined text-2xl text-pink-500">palette</span>
                    </div>
                    <div
                        class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex-1">
                        <span class="text-xs font-bold text-pink-500 uppercase mb-1 block">Step 4</span>
                        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2">Content, Design &
                            Brainstorming</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Our team works with you on content structure, design direction, and layout. We brainstorm
                            together.
                        </p>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="relative flex gap-6">
                    <div
                        class="flex-none w-16 h-16 rounded-xl bg-white dark:bg-[#1c1b2e] border-2 border-orange-500 shadow-lg flex items-center justify-center z-10">
                        <span class="material-symbols-outlined text-2xl text-orange-500">check_circle</span>
                    </div>
                    <div
                        class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex-1">
                        <span class="text-xs font-bold text-orange-500 uppercase mb-1 block">Step 5</span>
                        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2">Final Approval</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            You review the proposed design and content. Once approved, we lock everything and prepare
                            for development.
                        </p>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="relative flex gap-6">
                    <div
                        class="flex-none w-16 h-16 rounded-xl bg-white dark:bg-[#1c1b2e] border-2 border-cyan-500 shadow-lg flex items-center justify-center z-10">
                        <span class="material-symbols-outlined text-2xl text-cyan-500">engineering</span>
                    </div>
                    <div
                        class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex-1">
                        <span class="text-xs font-bold text-cyan-500 uppercase mb-1 block">Step 6</span>
                        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2">Work Starts</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Our team begins development, setup, and configuration while keeping performance, security,
                            and scalability in mind.
                        </p>
                    </div>
                </div>

                <!-- Step 7 -->
                <div class="relative flex gap-6">
                    <div
                        class="flex-none w-16 h-16 rounded-xl bg-white dark:bg-[#1c1b2e] border-2 border-accent-green shadow-lg flex items-center justify-center z-10">
                        <span class="material-symbols-outlined text-2xl text-accent-green">rocket_launch</span>
                    </div>
                    <div
                        class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm flex-1">
                        <span class="text-xs font-bold text-accent-green uppercase mb-1 block">Step 7</span>
                        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2">Your Website Goes Live</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            After final checks, your website is launched. Ongoing hosting and maintenance are handled by
                            us.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Call to Actions -->
            <div class="mt-20 flex flex-col md:flex-row items-center justify-center gap-6">
                <a href="<?php echo baseUrl('consultation.php'); ?>"
                    class="px-8 py-4 rounded-xl bg-accent-green text-white font-bold text-lg hover:opacity-90 shadow-lg shadow-accent-green/20 transition-all w-full md:w-auto text-center">
                    Book a Free Consultation
                </a>
                <a href="<?php echo baseUrl('index.php#pricing'); ?>"
                    class="px-8 py-4 rounded-xl bg-white dark:bg-white/5 border-2 border-primary text-primary dark:text-white font-bold text-lg hover:bg-primary/5 transition-all w-full md:w-auto text-center">
                    View Our Plans
                </a>
            </div>

        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>