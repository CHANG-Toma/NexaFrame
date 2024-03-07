<section class="page-list-container">
    <div class="page-list-header">
        <h1>Mes pages</h1>
        <div class="error <?php echo isset($_SESSION["error_message"]) ? '' : 'hidden'; ?>">
            <?php
            if (isset($_SESSION['error_message'])) {
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
            }
            ?>
        </div>
        <div class="success <?php echo isset($_SESSION["success_message"]) ? '' : 'hidden'; ?>">
            <?php
            if (isset($_SESSION['success_message'])) {
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            }
            ?>
        </div>
        <button class="btn-create-page">Create page</button>
    </div>

    <div class="page-list-search">
        <input type="text" placeholder="Page name">
        <button>Rechercher</button>
    </div>
    
    <div class="page-list-table">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Lien</th>
                    <th>Status</th>
                    <th>Créer le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Faire une boucle pour chaque page -->
                <?php
                for ($i = 0; $i < count($data); $i++) { ?>
                    <tr>
                        <td>
                            <?php echo $data[$i]['title']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['url']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['meta_description']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['created_at']; ?>
                        </td>
                        <td>
                            <form method="POST" Action="/dashboard/page-builder/delete-page">
                                <input type="hidden" name="id-page" value="<?php echo $data[$i]['id']; ?>">
                                <button type="submit">Modifier</button>
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>