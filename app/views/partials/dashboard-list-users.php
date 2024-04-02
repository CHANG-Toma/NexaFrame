<section class="page-list-container">
    <div class="page-list-header">
        <h1>Les utilisateurs :</h1>
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
    </div>

    <div class="page-list-search">
        <input id="searchInput" type="text" placeholder="Login de l'utilisateur recherché...">
    </div>

    <div class="page-list-table">
        <table id="pageTable">
            <thead>
                <tr>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Role</th>
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
                            <?php echo $data[$i]['login']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['email']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['role']; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['updated_at'] ? date('d F Y H:i:s', strtotime($data[$i]['updated_at'])) : ''; ?>
                        </td>
                        <td>
                            <?php echo $data[$i]['created_at'] ? date('d F Y H:i:s', strtotime($data[$i]['created_at'])) : ''; ?>
                        </td>
                        <td class="container">
                            <form method="POST" Action="#">
                                <input type="hidden" name="id-user" value="<?php echo $data[$i]['id']; ?>">
                                <button class="Button-sm update" type="submit">Modifier</button>
                            </form>
                            <form method="POST" Action="#">
                                <input type="hidden" name="id-user" value="<?php echo $data[$i]['id']; ?>">
                                <button class="Button-sm delete" type="submit">Soft Delete</button>
                            </form>

                            <form method="POST" Action="/dashboard/hard-delete">
                                <input type="hidden" name="id-user" value="<?php echo $data[$i]['id']; ?>">
                                <button class="Button-sm delete" type="submit">Hard Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>