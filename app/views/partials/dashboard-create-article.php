
<section class="container-flex">
    <div class="user-info-container">
        <h2>Créer un article</h2>
        <div class="user-info-form">
        <form action="/dashboard/article/create" method="post">
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Contenu</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                <div class="form-group">
                    <label for="keywords">Mots-clés</label>
                    <input type="text" id="keywords" name="keywords" required>
                </div>
                <div class="form-group">
                    <label for="picture_url">URL de l'Image</label>
                    <input type="text" id="picture_url" name="picture_url" required>
                </div>
                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <select id="category" name="category">
                        <?php foreach ($data as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo $category['label']; ?></option>
                        <?php endforeach; ?>
                    </select>   
                </div>
                <button type="submit" class="btn-update">Créer l'Article</button>
            </form>
        </div>
    </div>
</section>