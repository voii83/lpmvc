<h1>Список новостей</h1>

<?php foreach ($newsList as $news) : ?>
<div class="post">
	<div class="post__title"><a href="/news/<?= $news['id'] ?>"><?= $news['title'] ?></a></div>
	<div class="post__date"><?= $news['date'] ?></div>
	<div class="post__preview-text"><?= $news['short_content'] ?></div>
</div>
<?php endforeach; ?>