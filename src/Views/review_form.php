<?php if ($product): ?>
    <h1><?php echo $product->getName(); ?></h1>
    <img class="card-img-top" src="<?php echo $product->getimageUrl(); ?>" alt="Card image">
    <p><?php echo $product->getPrice(); ?></p>
    <p><?php echo $product->getDescription(); ?></p>

    <h2>Отзывы</h2>
    <?php if (empty($reviews)): ?>
        <p>На данный момент не отзывов,напишите первым и поставьте оценку продукту!</p>
    <?php else: ?>
        <p class="average-rating">Средняя оценка продукта по отзывам : <?php echo $averageRating; ?></p>
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <p><strong><?php echo $review->getAuthor(); ?></strong> (Оценка: <?php echo $review->getRating(); ?>/5)</p>
                <p><?php echo $review->getProductReview(); ?></p>
                <p><em><?php echo $review->getDate(); ?></em></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h3>Оставить отзыв</h3>
    <form action="/reviews" method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
        <label for="rating">Оценка (1-5):</label>
        <?php if (isset($errors['rating'])): ?>
            <span class="error"><?php echo $errors['rating']; ?></span>
        <?php endif; ?><br>
        <input type="number" name="rating" min="1" max="5" required><br>

        <label for="author">Ваше имя:</label>
        <input type="text" name="author" required><br>

        <label for="product_review">Ваш отзыв:</label><br>
        <textarea name="product_review" required></textarea><br>
        <?php if (isset($errors['product_review'])): ?>
            <span class="error"><?php echo $errors['product_review']; ?></span>
        <?php endif; ?><br>

        <button type="submit">Отправить отзыв</button>
    </form>

<?php else: ?>
    <p>Продукт не найден.</p>
<?php endif; ?>
