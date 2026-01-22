            </div>
        </main>
    </div>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const htmlElement = document.documentElement;

        // Check localStorage for dark mode preference
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            htmlElement.classList.add('dark');
        }

        darkModeToggle.addEventListener('click', () => {
            htmlElement.classList.toggle('dark');
            const isDark = htmlElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark);
        });

        // Close dropdown on click outside
        document.addEventListener('click', (e) => {
            const dropdown = document.querySelector('[class*="group-hover"]');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
