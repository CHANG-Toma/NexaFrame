<section class="page-list-container">
    <div class="page-list-header">
        <h1>Mes pages</h1>
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
        <form method="POST" Action="/dashboard/page-builder/create-page">
            <button class="Button-back-office btn-create-page" type="submit">Create page</button>
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
                    <th>Lien</th>
                    <th>Description</th>
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
                            <?php echo $data[$i]['url']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['meta_description']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['created_at']; ?>
                        </td>
                        <td class="container">
                            <button class="Button-sm update" 
                                data-id="<?php echo $data[$i]['id']; ?>"
                                data-html="<?php echo htmlspecialchars($data[$i]['html']); ?>"
                                data-css="<?php echo htmlspecialchars($data[$i]['css']); ?>">
                                Modifier
                            </button>


                            <form method="POST" Action="/dashboard/page-builder/delete-page">
                                <input type="hidden" name="id-page" value="<?php echo $data[$i]['id']; ?>">
                                <button class="Button-sm delete" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>