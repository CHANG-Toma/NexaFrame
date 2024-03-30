<section class="page-list-container">
    <div class="page-list-header">
        <h1>Mes articles</h1>
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
        <form method="POST" Action="/dashboard/create-article">
            <button class="Button-back-office btn-create-page" type="submit">Créer un article</button>
        </form>
    </div>

    <div class="page-list-search">
        <input id="searchInput" type="text" placeholder="Nom de votre page">
    </div>

    <div class="page-list-table">
        <table id="pageTable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Contenue</th>
                    <th>Mot clés</th>
                    <th>Modifié le</th>
                    <th>Créer le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($data); $i++) { ?>
                    <tr>
                        <td>
                            <?php echo $data[$i]['title']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['content']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['keywords']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['updated_at'] ? date('d F Y H:i:s', strtotime($data[$i]['updated_at'])) : ''; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['created_at'] ? date('d F Y H:i:s', strtotime($data[$i]['created_at'])) : ''; ?>
                        </td>
                        <td class="container">
                            <form method="POST" Action="/dashboard/delete-article">
                                <input type="hidden" name="id-article" value="<?php echo $data[$i]['id']; ?>">
                                <button class="Button-sm delete" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>