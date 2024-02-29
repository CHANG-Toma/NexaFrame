<footer class="footer">
    <div class="content">
        <div class="column logo-column">
            <div class="logo">
                <div class="logo">
                    <a href="/about">
                        <img class="icon logo" src="/assets/img/Nexaframe.png">
                    </a>
                </div>
            </div>
            <div class="content-info">
                <div class="address-block">
                    <div class="address">Address:</div>
                    <div class="address-detail">
                        <?= $adresse ?? '18 Rue des étudiants, 12345 France' ?>
                    </div>
                </div>
                <div class="contact-block">
                    <div class="contact">Contact:</div>
                    <div class="contact-info">
                        <div class="phone">
                            <?= $phone ?? '01 02 03 04 05' ?>
                        </div>
                        <div class="email">
                            <?= $email ?? 'contact@nexaframe.fr' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column links-column">
            <div class="link-list">
                <a href="/home" class="link">Home</a>
                <a href="#" class="link">About Us</a>
                <a href="#" class="link">Projects</a>
                <a href="#" class="link">Contact Us</a>
            </div>
        </div>
    </div>


    <div class="credits">
        <div class="divider"></div>
        <div class="row">
            <div class="copyright">© 2024 Simplify. All rights reserved.</div>
            <div class="footer-links">
                <div class="footer-link">
                    <a href="#">Privacy Policy</a>
                </div>
                <div class="footer-link">
                    <a href="#">Terms of Service</a>
                </div>
                <div class="footer-link">
                    <div class="footer-link">
                        <a href="#">Cookies Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </>