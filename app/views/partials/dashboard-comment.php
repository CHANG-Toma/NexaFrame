<section class="comments-management-container">
    <h2>Gestion des Commentaires</h2>
    <div class="comments-list">
        
        <?php    
        foreach ($comments as $comment) {
            ?>
            <div class="comment-item">
                <div class="comment-content">
                    <p class="comment-text"><?= $comment['content']; ?></p>
                    <div class="comment-info">
                        <span class="comment-author">Auteur: <?= $comment['author']; ?></span>
                        <span class="comment-article">Article : <?= $comment['articleTitle']; ?></span>
                        <span class="comment-date">Date : <?= $comment['created_at']; ?></span>
                    </div>
                </div>
                <div class="comment-actions">
                    <button class="btn approve-comment">Approuver</button>
                    <button class="btn delete-comment">Supprimer</button>
                </div>
            </div>
            <?php
        }
        ?>


    </div>
</section>