<?php
/**
 * Cookie Policy Page
 */

$pageTitle = 'Cookie Policy | SiteOnSub';
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';
?>

<main class="flex-1 py-16 px-6 md:px-20">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-[#0f0e1b] dark:text-white mb-4">
                Cookie Policy
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
                    This Cookie Policy explains what Cookies are and how We use them. You should read this policy so You
                    can understand what type of cookies We use, or the information We collect using Cookies and how that
                    information is used.
                </p>

                <!-- Section 1 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">1</span>
                        What are Cookies?
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        Cookies are small text files that are placed on your computer or mobile device by websites that
                        you visit. They are widely used in order to make websites work, or work more efficiently, as
                        well as to provide information to the owners of the site. Cookies do not typically contain any
                        information that personally identifies a user, but personal information that we store about You
                        may be linked to the information stored in and obtained from Cookies.
                    </p>
                </div>

                <!-- Section 2 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">2</span>
                        How We Use Cookies
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        We use both session and persistent Cookies for the purposes set out below:
                    </p>

                    <div class="space-y-6">
                        <!-- Cookie Type 1 -->
                        <div
                            class="bg-gray-50 dark:bg-white/5 p-6 rounded-xl border border-gray-100 dark:border-white/5">
                            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">toggle_on</span>
                                Necessary / Essential Cookies
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Type: Session Cookies |
                                Administered by: Us</p>
                            <p class="text-gray-600 dark:text-gray-300">
                                These Cookies are essential to provide You with services available through the Website
                                and to enable You to use some of its features. They help to authenticate users and
                                prevent fraudulent use of user accounts. Without these Cookies, the services that You
                                have asked for cannot be provided.
                            </p>
                        </div>

                        <!-- Cookie Type 2 -->
                        <div
                            class="bg-gray-50 dark:bg-white/5 p-6 rounded-xl border border-gray-100 dark:border-white/5">
                            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">settings</span>
                                Functionality Cookies
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Type: Persistent Cookies |
                                Administered by: Us</p>
                            <p class="text-gray-600 dark:text-gray-300">
                                These Cookies allow us to remember choices You make when You use the Website, such as
                                remembering your login details or language preference. The purpose of these Cookies is
                                to provide You with a more personal experience and to avoid You having to re-enter your
                                preferences every time You use the Website.
                            </p>
                        </div>

                        <!-- Cookie Type 3 -->
                        <div
                            class="bg-gray-50 dark:bg-white/5 p-6 rounded-xl border border-gray-100 dark:border-white/5">
                            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">analytics</span>
                                Analytics Cookies
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Type: Persistent Cookies |
                                Administered by: Third-Parties</p>
                            <p class="text-gray-600 dark:text-gray-300">
                                These cookies may be used to collect information about how you use our website, for
                                example, which pages you visit most often. This data may be used to help optimize our
                                website and make it easier for you to navigate.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 3 -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary font-black text-lg">3</span>
                        Your Choices Regarding Cookies
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                        If You prefer to avoid the use of Cookies on the Website, first You must disable the use of
                        Cookies in your browser and then delete the Cookies saved in your browser associated with this
                        website. You may use this option for preventing the use of Cookies at any time.
                    </p>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        If You do not accept Our Cookies, You may experience some inconvenience in your use of the
                        Website and some features may not function properly.
                    </p>
                </div>

                <!-- Contact Section -->
                <div class="mt-12 bg-primary/5 rounded-2xl p-8 border border-primary/10">
                    <h3 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-4">
                        More Information
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        If you have any questions about our Cookie Policy, please contact us:
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">email</span>
                            <span class="text-gray-600 dark:text-gray-300"><strong>Email:</strong>
                                support@siteonsub.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>