<?php
require_once '../admin/db_connect.php';
include 'regmem_frame.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'] ?? 0;
    $feedback = trim($_POST['feedback'] ?? '');

    if ($rating >= 1 && $rating <= 5 && !empty($feedback)) {
        $stmt = $conn->prepare("INSERT INTO feedbacks (user_id, rating, feedback) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $rating, $feedback);
        $stmt->execute();
        $stmt->close();

        // ✅ Don't redirect — just stay on page
        // Optional: set success message
        $success = true;
    }
}

// ✅ Fetch all feedbacks
$reviews = [];
$result = $conn->query("SELECT rating, feedback FROM feedbacks ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>FCSIT Kiosk - Review Page</title>

    <!-- Tailwind CSS & Plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#002f6c',
                        secondary: '#c9dafc'
                    }
                }
            }
        };
    </script>

    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="review.css">
</head>
<body class="bg-secondary min-h-screen">

<!-- TODO: Insert your navbar here -->

<main class="max-w-6xl mx-auto px-4 pt-20 pb-12 flex flex-col md:flex-row gap-8">
    <!-- Rating & Feedback -->
    <section class="bg-white rounded-xl border border-gray-300 max-w-md w-full shadow-sm">
        <header class="bg-primary rounded-t-xl py-4 text-center pixel-font text-white text-lg tracking-widest select-none">
            RATING & FEEDBACK
        </header>
        <form method="POST" class="p-6 space-y-6">
            <div class="text-center text-base text-black font-serif">Rate your meal experience:</div>
            <div class="flex justify-center space-x-1 text-gray-300 text-2xl select-none" id="stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star cursor-pointer" data-value="<?= $i ?>"></i>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="rating" id="ratingInput" value="0">

            <div class="text-center text-base text-black font-serif">Write your feedback:</div>
            <textarea name="feedback" class="w-full border border-gray-400 rounded resize-none text-xs p-2 font-serif text-black" placeholder="Type here" rows="4" required></textarea>

            <div class="flex justify-center">
                <button type="submit" class="bg-primary text-white font-bold text-xs px-6 py-1 rounded-full tracking-widest hover:opacity-90 transition">
                    SUBMIT
                </button>
            </div>
        </form>
    </section>

    <!-- Feedback Display -->
    <section class="flex-1 space-y-6 text-black font-serif text-sm">
        <h2 class="mb-2 text-lg">What others are saying:</h2>
        <?php foreach ($reviews as $review): ?>
            <article class="space-y-1">
                <div class="flex items-center space-x-2 text-gray-700">
                    <i class="fas fa-user-circle text-xl"></i>
                    <div class="flex space-x-1 text-yellow-600 text-lg">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $review['rating']): ?>
                                <i class="fas fa-star"></i>
                            <?php else: ?>
                                <i class="far fa-star text-gray-400"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
                <p class="text-gray-500 leading-relaxed max-w-xl"><?= htmlspecialchars($review['feedback']) ?></p>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<!-- Rating JS -->
<script>
    const stars = document.querySelectorAll('#stars .fa-star');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const rating = parseInt(star.getAttribute('data-value'));
            ratingInput.value = rating;

            stars.forEach((s, i) => {
                s.classList.toggle('text-yellow-500', i < rating);
                s.classList.toggle('text-gray-300', i >= rating);
            });
        });
    });
</script>

</body>
</html>
