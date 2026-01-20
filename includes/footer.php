<footer class="container">
        <p>&copy; <?php echo date('Y'); ?> Sydney Social Activities Hub</p>
    </footer>
</body>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/Syd_Social/sw.js')
      .then(reg => console.log('Service Worker registered!', reg))
      .catch(err => console.log('Service Worker registration failed:', err));
  });
}
</script>
</html>