<section class="container-flex">
    <div class="user-info-container">
        <h2>Gérer mon article</h2>
        <div class="user-info-form">
            <form action="/dashboard/article/create" method="post">
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" required
                        value="<?php echo !empty($dataArticle) ? $dataArticle[0]['title'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="content">Contenu</label>
                    <textarea id="content" name="content"
                        required><?php echo !empty($dataArticle) ? $dataArticle[0]['content'] : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="keywords">Mots-clés</label>
                    <input type="text" id="keywords" name="keywords" required
                        value="<?php echo !empty($dataArticle) ? $dataArticle[0]['keywords'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="picture_url">URL de l'Image</label>
                    <input type="text" id="picture_url" name="picture_url" required
                        value="<?php echo !empty($dataArticle) ? $dataArticle[0]['picture_url'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <select id="category" name="category">
                        <?php foreach ($data as $category): ?>
                            <option value="<?php echo $category['id']; ?>">
                                <?php echo $category['label']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="id-article"
                    value="<?php echo !empty($dataArticle) ? $dataArticle[0]['id'] : ''; ?>"
                    class="btn-update">Enregistrer</button>
            </form>
        </div>
    </div>
</section>