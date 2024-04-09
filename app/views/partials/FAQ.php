<section class="Faq">
    <div class="Container">
        <div class="title">
            <h2 class="heading heading-centered">FAQs</h2>
            <p class="text">Trouvez des réponses aux questions fréquentes et aux préoccupations concernant notre plateforme.</p>
        </div>
        <div class="Accordion">
            <div class="faq-item">
                <button class="faq-question">Comment m'inscrire ?</button>
                <div class="faq-answer" style="display: none;">Vous pouvez vous inscrire en cliquant sur le bouton "Commencer" sur notre page d'accueil.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Comment réinitialiser mon mot de passe ?</button>
                <div class="faq-answer" style="display: none;">En cliquant sur le lien "Mot de passe oublié" sur la page de connexion.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Offrez-vous un support client ?</button>
                <div class="faq-answer" style="display: none;">Oui, nous offrons un support client 24/7 par e-mail.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Cette application est-elle gratuite d'utilisation ? </button>
                <div class="faq-answer" style="display: none;">
                    Oui, vous pouvez profiter de toutes les fonctionnalités sans aucun frais.
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const faqQuestions = document.querySelectorAll('.faq-question');

        faqQuestions.forEach(question => {
            question.addEventListener('click', function () {
                // Trouver l'élément frère direct (la réponse) de la question cliquée
                const answer = this.nextElementSibling;

                // Vérifier si la réponse est déjà ouverte
                if (answer.style.display === 'block') {
                    answer.style.display = 'none';
                    this.classList.remove('is-open');
                } else {
                    // Fermer toutes les réponses ouvertes avant d'ouvrir la nouvelle
                    const allAnswers = document.querySelectorAll('.faq-answer');
                    allAnswers.forEach(ans => ans.style.display = 'none');

                    // Enlever la classe 'is-open' de tous les boutons avant d'ajouter au courant
                    faqQuestions.forEach(btn => btn.classList.remove('is-open'));

                    // Ouvrir la réponse cliquée
                    answer.style.display = 'block';
                    this.classList.add('is-open');
                }
            });
        });
    });

</script>