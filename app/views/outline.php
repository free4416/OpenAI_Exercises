<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outline Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">

        <h1 class="my-4">Outline Generator</h1>
        <form action="index.php" method="post">
            <div class="form-group">
                <label for="video_url">YouTube Video URL</label>
                <input type="text" class="form-control" name="video_url" id="video_url" placeholder="Enter YouTube video URL" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Generate Outline</button>
        </form>

        <?php if (isset($transcribed_text)): ?>
            <h2 class="my-4">Transcribed Text</h2>
            <pre><?php echo $transcribed_text; ?></pre>
        <?php endif; ?>

        <?php if (isset($outline)): ?>
            <h2 class="my-4">Outline Summary</h2>
            <pre><?php echo $outline; ?></pre>
        <?php endif; ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
