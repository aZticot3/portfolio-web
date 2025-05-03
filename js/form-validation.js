document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('collaboration-form');
    if (!form) return;

    // Fonction pour valider un champ
    const validateField = (field, errorId, validationFn) => {
        const errorElement = document.getElementById(errorId);
        const isValid = validationFn(field.value);
        
        if (!isValid.valid) {
            errorElement.textContent = isValid.message;
            field.classList.add('invalid');
            return false;
        } else {
            errorElement.textContent = '';
            field.classList.remove('invalid');
            return true;
        }
    };

    // Règles de validation
    const validationRules = {
        desc: (value) => {
            if (!value.trim()) return { valid: false, message: 'La description du projet est obligatoire' };
            if (value.trim().length < 10) return { valid: false, message: 'La description doit contenir au moins 10 caractères' };
            if (value.trim().length > 1000) return { valid: false, message: 'La description ne doit pas dépasser 1000 caractères' };
            return { valid: true };
        },
        tech: (value) => {
            if (!value.trim()) return { valid: false, message: 'Précisez les technologies utilisées' };
            return { valid: true };
        },
        user_name: (value) => {
            if (!value.trim()) return { valid: false, message: 'Votre nom est obligatoire' };
            return { valid: true };
        },
        tel: (value) => {
            if (!value.trim()) return { valid: false, message: 'Votre téléphone est obligatoire' };
            // Vérification simple pour un numéro français
            const regex = /^[0-9]{10}$/;
            if (!regex.test(value.replace(/\s/g, ''))) {
                return { valid: false, message: 'Format de téléphone invalide (10 chiffres)' };
            }
            return { valid: true };
        },
        email: (value) => {
            if (!value.trim()) return { valid: false, message: 'Votre email est obligatoire' };
            // Vérification de l'email avec regex
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regex.test(value)) {
                return { valid: false, message: 'Format d\'email invalide' };
            }
            return { valid: true };
        }
    };

    // Ajout des écouteurs d'événements pour la validation en temps réel
    const fields = [
        { id: 'desc', errorId: 'desc-error', rule: validationRules.desc },
        { id: 'tech', errorId: 'tech-error', rule: validationRules.tech },
        { id: 'user_name', errorId: 'user_name-error', rule: validationRules.user_name },
        { id: 'tel', errorId: 'tel-error', rule: validationRules.tel },
        { id: 'email', errorId: 'email-error', rule: validationRules.email }
    ];

    // Validation en temps réel lors de la saisie (après avoir quitté le champ)
    fields.forEach(field => {
        const element = document.getElementById(field.id);
        if (element) {
            element.addEventListener('blur', function() {
                validateField(this, field.errorId, field.rule);
            });

            // Pour les champs de saisie, validation après un délai de frappe
            if (element.tagName === 'INPUT') {
                element.addEventListener('input', function() {
                    clearTimeout(this.timer);
                    this.timer = setTimeout(() => {
                        validateField(this, field.errorId, field.rule);
                    }, 500);
                });
            }
        }
    });

    // Validation complète lors de la soumission du formulaire
    form.addEventListener('submit', function(e) {
        let isFormValid = true;
        
        // Valider tous les champs
        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                const fieldValid = validateField(element, field.errorId, field.rule);
                if (!fieldValid) isFormValid = false;
            }
        });
        
        // Si le formulaire n'est pas valide, empêcher l'envoi
        if (!isFormValid) {
            e.preventDefault();
            
            // Faire défiler jusqu'au premier champ en erreur
            const firstErrorField = document.querySelector('.invalid');
            if (firstErrorField) {
                firstErrorField.focus();
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Afficher un message d'alerte global
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.classList.add('error-shake');
            setTimeout(() => {
                submitBtn.classList.remove('error-shake');
            }, 500);
        }
    });
});