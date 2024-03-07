<section class="page-list-container">
    <div class="page-list-header">
        <h1>Mes pages</h1>
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
                    <th>Menu</th>
                    <th>Lien</th>
                    <th>Status</th>
                    <th>Créer le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Faire une boucle pour chaque page -->
                <tr>
                    <td>testing2</td>
                    <td>No menu</td>
                    <td>/testing2</td>
                    <td>UNPUBLISHED</td>
                    <td>2022-09-20 13:57</td>
                    <td>
                        <button>Modifier</button>
                        <button>Supprimer</button>
                    </td>
                </tr>
                <?php 
                print_r($data);
                ?>
            </tbody>
        </table>
    </div>
</section>