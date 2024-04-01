<section class="comments-management-container">
    <h2>Gestion des Commentaires</h2>

    <div class="error <?php session_start();
    echo isset($_SESSION["error_message"]) ? '' : 'hidden'; ?>">
        <?php
        if (isset($_SESSION['error_message'])) {
            echo $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }
        ?>
    </div>
    <div class="success <?php session_start();
    echo isset($_SESSION["success_message"]) ? '' : 'hidden'; ?>">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        ?>
    </div>

    <div class="comments-list">
        <?php
        if (isset($comments) && !empty($comments)) {
            foreach ($comments as $comment) {
                ?>
                <div class="comment-item">
                    <div class="comment-content">
                        <p class="comment-text">
                            <?= $comment['content']; ?>
                        </p>
                        <div class="comment-info">
                            <span class="comment-author">Auteur:
                                <?= $comment['author']; ?>
                            </span>
                            <span class="comment-article">Article :
                                <?= $comment['articleTitle']; ?>
                            </span>
                            <span class="comment-date">Date :
                                <?= $comment['created_at']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="comment-actions">
                        <form action="/dashboard/comment/approve" method="post">
                            <input type="hidden" name="comment_id" value="<?= $comment['id']; ?>">
                            <?php
                            if (!$comment['valid']): ?>
                                <button type="submit" class="btn approve-comment">Approuver</button>
                            <?php else: ?>
                                <span class="already-approved">approuvé</span>
                            <?php endif; ?>
                        </form>

                        <form action="/dashboard/comment/delete" method="post">
                            <input type="hidden" name="comment_id" value="<?= $comment['id']; ?>">
                            <button type="submit" class="btn delete-comment">Supprimer</button>
                        </form>
                    </div>
                </div>
                <?php
            }
        }
        else {
            echo "Aucun commentaire trouvé";
        }
        ?>


    </div>
</section>