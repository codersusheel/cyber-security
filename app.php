<?php
// डेटा स्टोर करने के लिए JSON फ़ाइल
$dataFile = 'data.json';
$cards = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

// फॉर्म सबमिट होने पर
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $link = $_POST['link'] ?? '';
    $photoPath = '';

    // फोटो अपलोड हैंडलिंग
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $photoPath = $uploadDir . $fileName;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    // नया डेटा लिस्ट में जोड़ना
    $newCard = [
        'title' => htmlspecialchars($title),
        'description' => htmlspecialchars($description),
        'link' => htmlspecialchars($link),
        'photo' => $photoPath
    ];

    array_unshift($cards, $newCard); // नया कार्ड ऊपर दिखेगा
    file_put_contents($dataFile, json_encode($cards));
    header("Location: " . $_SERVER['PHP_SELF']); // रीफ्रेश से बचाने के लिए
    exit();
}
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>PHP Card Project</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; background: #f4f4f9; }
        .form-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="url"], textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        
        /* कार्ड ग्रिड और स्टाइल */
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card img { width: 100%; height: 160px; object-fit: cover; }
        .card-body { padding: 15px; }
        .card-title { font-size: 18px; margin: 0 0 10px 0; font-weight: bold; }
        .card-desc { color: #555; font-size: 14px; margin-bottom: 15px; }
        .card-btn { display: inline-block; background: #007bff; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>

    <!-- इनपुट फॉर्म -->
    <div class="form-container">
        <h2>नया कार्ड जोड़ें</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Link:</label>
                <input type="url" name="link" placeholder="https://example.com" required>
            </div>
            <div class="form-group">
                <label>Photo:</label>
                <input type="file" name="photo" accept="image/*" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- कार्ड्स की लिस्ट -->
    <h2>सबमिट किए गए कार्ड्स</h2>
    <div class="cards-grid">
        <?php foreach ($cards as $card): ?>
            <div class="card">
                <?php if ($card['photo']): ?>
                    <img src="<?= $card['photo'] ?>" alt="Card Image">
                <?php endif; ?>
                <div class="card-body">
                    <div class="card-title"><?= $card['title'] ?></div>
                    <div class="card-desc"><?= $card['description'] ?></div>
                    <a href="<?= $card['link'] ?>" target="_blank" class="card-btn">Visit Link</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
