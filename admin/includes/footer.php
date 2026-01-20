</div> <!-- End flex-1 container -->
</div> <!-- End min-h-screen container -->

<script>
    // Optional sidebar toggle for mobile
    const menuBtn = document.querySelector('button.md\\:hidden');
    const sidebar = document.querySelector('aside');
    if (menuBtn && sidebar) {
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
</script>
</body>

</html>