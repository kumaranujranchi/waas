<?php
// Simple Array Input Tester
?>
<!DOCTYPE html>
<html>

<head>
    <title>Array Test</title>
</head>

<body>
    <h1>Array Input Test</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<h2>Results:</h2>";
        echo "<pre style='background:#eee; padding:10px;'>";
        print_r($_POST);
        echo "</pre>";

        if (isset($_POST['items']) && is_array($_POST['items'])) {
            echo "<h3 style='color:green'>✅ Array 'items' detected!</h3>";
            echo "<p>Count: " . count($_POST['items']) . "</p>";
        } else {
            echo "<h3 style='color:red'>❌ Array 'items' NOT detected.</h3>";
        }
    }
    ?>

    <hr>

    <form method="POST" enctype="multipart/form-data">
        <p>Type something below (Multipart Mode):</p>
        <input type="text" name="items[]" value="Item 1"><br><br>
        <input type="text" name="items[]" value="Item 2"><br><br>
        <input type="text" name="items[]" value="Item 3"><br><br>
        <button type="submit">Submit Test</button>
    </form>
</body>

</html>